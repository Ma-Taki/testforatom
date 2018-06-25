<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Tr_search_categories;
use App\Models\Tr_search_categories_display;
use App\Models\Tr_link_items_search_categories;
use App\Http\Requests\admin\CategoryRegistRequest;
use App\Http\Requests\admin\CopyCategoryRegistRequest;
use App\Libraries\AdminUtility as AdminUtil;
use DB;
use Carbon\Carbon;
use Log;
use Session;


class CategoryController extends AdminController
{
    /**
     * 検索一覧画面・処理
     * GET,POST:/admin/category/search
     */
    public function searchCategory(Request $request){
    
        $data_query = [
            'name'        => $request->name ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];

        $query = Tr_search_categories::select('search_categories.*');
                                                    
        if(!empty($request->name)){
            //検索「親または子カテゴリー名」に入力したとき
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $categoryList = $query->orderBy('parent_sort', 'asc')->orderBy('child_sort', 'asc')->paginate(20);
                                 
        return view('admin.category_list',compact('categoryList','data_query'));
    }
    
    /**
     * 詳細画面表示
     * GET:/admin/category/detail
     */
    public function showCategoryDetail(Request $request){
        $category = Tr_search_categories::where('id', $request->id)->get()->first();

        if(!empty($request->parent_id)){
            //子のとき 親のカテゴリー名を取得
            $parent = Tr_search_categories::where('id', $request->parent_id)->get()->first();
        }
        if (empty($category)) {
            abort(404, '指定された案件は存在しません。');
        }
        return view('admin.category_detail', compact('category','parent'));
    }

    /**
     * 新規登録画面表示
     * GET:/admin/category/input
     */
    public function showCategoryInput(Request $request){

        if($request->type == 'parent'){
            //親のとき
            //親カテゴリー最大表示順を取得
            $sortMax = Tr_search_categories::max('parent_sort');
            $sortMax = $sortMax + 1;
        }elseif($request->type == 'child'){
            //子のとき
            //親カテゴリーのみを昇順で取得
            $parents = Tr_search_categories::parent()->get();
            //親に対する子カテゴリー最大表示順を取得
            $sortMax = Tr_search_categories::where('parent_sort', 1)->where('child_sort', '!=', null)->max('child_sort');
            
            $sortMax = $sortMax + 1;
        }

        return view('admin.category_input', compact('parents','sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/category/input
     */
    public function insertCategory(CategoryRegistRequest $request){
        
        $data = array(
                        'parent_id'         => $request->parent_id,
                        'delete_flag'       => false,
                        'name'              => $request->category_name,
                        'parent_sort'       => $request->parent_sort,
                        'child_sort'        => $request->child_sort,
                        'page_title'        => $request->page_title,
                        'page_keywords'     => $request->page_keywords,
                        'page_description'  => $request->page_description
                    );

        if(empty($data['parent_id']) && empty($data['child_sort'])){
            //親のとき
            //親の最大表示順
            $sortMax = Tr_search_categories::max('parent_sort');

            for($value = $data['parent_sort']; $value <= $sortMax; $value++){
                //表示順に紐づいた情報を取得
                $update_category = Tr_search_categories::where('parent_sort', $value)->get();
                foreach ($update_category as $update) {
                    $update_db[] = array(
                                    'id'          => $update->id,
                                    'parent_sort' => $value + 1,
                                    'child_sort'  => $update->child_sort
                                );
                }
            }
            
        }else{
            //子どものとき
            //登録する親の表示ステータスを確認
            $parentCategory = Tr_search_categories::where('id', $data['parent_id'])->get()->first();

            //非表示のとき
            if($parentCategory->delete_flag){
                $data['delete_flag'] = true;
            }

            //登録する親の子ども最大表示順を取得
            $maxSort = Tr_search_categories::where('parent_id', $data['parent_id'])->max('child_sort');

            for($value = $data['child_sort']; $value <= $maxSort; $value++){
                //表示順に紐づいた情報を取得
                $update_category = Tr_search_categories::where('parent_id', $data['parent_id'])
                                                            ->where('parent_sort', $data['parent_sort'])
                                                            ->where('child_sort', $value)
                                                            ->get()
                                                            ->first();
                $update_db[] = array(
                                    'id'          => $update_category->id,
                                    'parent_sort' => $update_category->parent_sort,
                                    'child_sort'  => $value + 1
                                );
            }
        }

        //挿入処理    
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_category = new Tr_search_categories;
                $insert_category->parent_id         = $data['parent_id'];
                $insert_category->delete_flag       = $data['delete_flag'];
                $insert_category->name              = $data['name'];
                $insert_category->parent_sort       = $data['parent_sort'];
                $insert_category->child_sort        = $data['child_sort'];
                $insert_category->page_title        = $data['page_title'];
                $insert_category->page_keywords     = $data['page_keywords'];
                $insert_category->page_description  = $data['page_description'];
                $insert_category->save();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        //更新処理
        if(!empty($update_db)){
            foreach ($update_db as $update) {
                //トランザクション
                DB::transaction(function () use ($update) {
                 try {
                        Tr_search_categories::where('id', $update['id'])->update([
                            'parent_sort' => $update['parent_sort'],
                            'child_sort'  => $update['child_sort']
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }

        return redirect('/admin/category/search')->with('custom_info_messages','カテゴリー登録は正常に終了しました。');
    }

    /**
     * コピーして新規登録画面表示(親)
     * GET:/admin/category/copy-parent-input
     */
    public function showCopyParentInput(Request $request){
        //親カテゴリー最大表示順を取得
        $sortMax = Tr_search_categories::max('parent_sort');
        $sortMax = $sortMax + 1;
        //コピー元の親情報
        $copyParent = Tr_search_categories::where('id', $request->id)->first();
        //コピー元の子情報
        $copysChild = Tr_search_categories::where('parent_id', $request->id)->get();
        
        return view('admin.category_copy_parent_input', compact('sortMax','copyParent','copysChild'));
    }

    /**
     * コピーして新規登録画面表示(子)
     * GET:/admin/category/copy-input
     */
    public function showCopyChildInput(Request $request){
        //親カテゴリーのみを昇順で取得
        $parents = Tr_search_categories::parent()->get();
        //親に対する子カテゴリー最大表示順を取得
        $sortMax = Tr_search_categories::where('parent_sort', 1)->where('child_sort', '!=', null)->max('child_sort');
        $sortMax = $sortMax + 1;
        //コピー元の子情報
        $copysChild[] = Tr_search_categories::where('id', $request->id)->first();
        //子のコピー数
        $copysChildNum = 0;

        return view('admin.category_copy_child_input', compact('copysChild','copysChildNum','parents','sortMax'));
    }

    /**
     * コピーして新規登録処理
     * POST:/admin/category/copy-child-input
     */
    public function insertCopyCategory(CopyCategoryRegistRequest $request){
        $parentFlag = false;
        //案件カテゴリー挿入用配列
        $insItem  = array();
        // *****************************************************************
        // 親画面から子画面へ遷移したときの表示
        // *****************************************************************
        //入力した親の情報
        if(!empty($request->children)){
            $ParentData = array(
                'parent_id'         => $request->parent_id[0],
                'delete_flag'       => false,        
                'name'              => $request->category_name[0],      
                'parent_sort'       => $request->parent_sort[0],
                'child_sort'        => $request->child_sort[0],
                'page_title'        => $request->page_title[0],
                'page_keywords'     => $request->page_keywords[0],
                'page_description'  => $request->page_description[0]
            );

            foreach ($request->children as $key => $id) {
                //コピー元の子情報
                $copysChild[] = Tr_search_categories::where('id', $id)->first();
                if($key == 0){
                    $sortMax = 1;
                }else{
                    $sortMax = $sortMax + 1;
                } 
            }
            
            //子のコピー数
            $copysChildNum = count($copysChild);
            //親カテゴリーを昇順で取得
            $parents = Tr_search_categories::parent()->get();

            //セッションへ保存
            Session::put('parentData', $ParentData);
            Session::put('copysChild', $copysChild);
            Session::put('copysChildNum', $copysChildNum);
            Session::put('copyParentId', $request->id);
            Session::put('sortMax', $sortMax);
            Session::put('parents', $parents);
            Session::put('parentName', $request->category_name[0]);
            return view('admin.category_copy_child_input');

        }else{
            // *****************************************************************
            //  登録ボタンを押したとき
            //  親画面から登録(親のみ)
            //  子画面から登録(子のみ)
            //  親画面・子画面から登録(親・子)
            // *****************************************************************
            if(empty($request->parent_id) && empty($request->child_sort)){
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                // 親のみのとき
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                //挿入用配列
                $onlyParent = array(
                    'parent_id'         => $request->parent_id[0],
                    'delete_flag'       => false,        
                    'name'              => $request->category_name[0],      
                    'parent_sort'       => $request->parent_sort[0],
                    'child_sort'        => $request->child_sort[0],
                    'page_title'        => $request->page_title[0],
                    'page_keywords'     => $request->page_keywords[0],
                    'page_description'  => $request->page_description[0]
                );

                //親の最大表示順
                $sortMax = Tr_search_categories::max('parent_sort');
                for($value = $onlyParent['parent_sort']; $value <= $sortMax; $value++){
                    //表示順に紐づいた情報を取得
                    $update_category = Tr_search_categories::where('parent_sort', $value)->get();
                    foreach ($update_category as $update) {
                        //更新用配列
                        $update_db[] = array(
                                        'id'          => $update->id,
                                        'parent_sort' => $value + 1,
                                        'child_sort'  => $update->child_sort
                                    );
                    }
                }   
            }else{
                //親のセッションデータがあるとき
                if(Session::has('parentData')){
                    // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                    // 親・子のとき
                    // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                    //挿入用配列(親)
                    $parent = array(
                        'parent_id'         => Session::get('parentData.parent_id'),
                        'delete_flag'       => false,        
                        'name'              => Session::get('parentData.name'),      
                        'parent_sort'       => Session::get('parentData.parent_sort'),
                        'child_sort'        => Session::get('parentData.child_sort'),
                        'page_title'        => Session::get('parentData.page_title'),
                        'page_keywords'     => Session::get('parentData.page_keywords'),
                        'page_description'  => Session::get('parentData.page_description')
                    );
                    //表示順重複チェック(子)
                    $unique_array = array_unique($request->child_sort);
                    if (count($unique_array) === count($request->child_sort)) {
                        for($count = 0; $count <= count($request->category_name) - 1; $count++){
                            //挿入用配列(子)表示順重複なし
                            $child[] = array(
                                'parent_id'         => null,
                                'delete_flag'       => false,        
                                'name'              => $request->category_name[$count],      
                                'parent_sort'       => Session::get('parentData.parent_sort'),
                                'child_sort'        => $request->child_sort[$count],
                                'page_title'        => $request->page_title[$count],
                                'page_keywords'     => $request->page_keywords[$count],
                                'page_description'  => $request->page_description[$count]
                            );
                        }
                    }else{
                        for($count = 0; $count <= count($request->category_name) - 1; $count++){
                            //挿入用配列(子)表示順重複あり
                            $child[] = array(
                                'parent_id'         => null,
                                'delete_flag'       => false,        
                                'name'              => $request->category_name[$count],      
                                'parent_sort'       => Session::get('parentData.parent_sort'),
                                'child_sort'        => $count + 1,
                                'page_title'        => $request->page_title[$count],
                                'page_keywords'     => $request->page_keywords[$count],
                                'page_description'  => $request->page_description[$count]
                            );
                        }
                    }

                    //親の最大表示順
                    $sortMax = Tr_search_categories::max('parent_sort');
                    for($value = $parent['parent_sort']; $value <= $sortMax; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_sort', $value)->get();
                        foreach ($update_category as $update) {
                            //更新用配列
                            $update_db[] = array(
                                            'id'          => $update->id,
                                            'parent_sort' => $value + 1,
                                            'child_sort'  => $update->child_sort
                                        );
                        }
                    }
                }else{
                    // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                    // 子のみのとき
                    // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                    //登録する親の表示ステータスを確認
                    $parentCategory = Tr_search_categories::where('id', $request->parent_id[0])->first();
                    if($parentCategory->delete_flag){
                        $delete_flag = true;
                    }else{
                        $delete_flag = false;
                    }

                    //挿入用配列
                    $onlyChild = array(
                        'parent_id'         => $request->parent_id[0],
                        'delete_flag'       => $delete_flag,        
                        'name'              => $request->category_name[0],      
                        'parent_sort'       => $request->parent_sort[0],
                        'child_sort'        => $request->child_sort[0],
                        'page_title'        => $request->page_title[0],
                        'page_keywords'     => $request->page_keywords[0],
                        'page_description'  => $request->page_description[0]
                    );
                    
                    //登録する親の子ども最大表示順を取得
                    $maxSort = Tr_search_categories::where('parent_id', $onlyChild['parent_id'])->max('child_sort');
                    for($value = $onlyChild['child_sort']; $value <= $maxSort; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_id', $onlyChild['parent_id'])
                                                                    ->where('parent_sort', $onlyChild['parent_sort'])
                                                                    ->where('child_sort', $value)->first();
                        //更新用配列
                        $update_db[] = array(
                                            'id'          => $update_category->id,
                                            'parent_sort' => $update_category->parent_sort,
                                            'child_sort'  => $value + 1,
                                        );
                    }
                    //コピー元と異なる親を選択したとき
                    $checkParent = Tr_search_categories::where('id', $request->id[0])->first();
                    if($request->parent_id[0] != $checkParent->parent_id){
                        $parentFlag = true;
                    }
                }  
            }

            // *****************************************************************
            // 挿入処理
            // *****************************************************************
            // ~*~*~*~*~*~*~*~*~*~*~*~*~*
            // 親・子のとき
            // ~*~*~*~*~*~*~*~*~*~*~*~*~*
            if(!empty($parent)) {
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                // 親・子の親の処理
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                $parentTran = DB::transaction(function () use ($parent) {
                    try {
                        //テーブルに挿入
                        $insert_category = new Tr_search_categories;
                        $insert_category->parent_id         = $parent['parent_id'];
                        $insert_category->delete_flag       = $parent['delete_flag'];
                        $insert_category->name              = $parent['name'];
                        $insert_category->parent_sort       = $parent['parent_sort'];
                        $insert_category->child_sort        = $parent['child_sort'];
                        $insert_category->page_title        = $parent['page_title'];
                        $insert_category->page_keywords     = $parent['page_keywords'];
                        $insert_category->page_description  = $parent['page_description'];
                        $insert_category->save();
                        return['id' => $insert_category->id];
                    } catch (Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });

                //コピーして登録する案件のカテゴリー情報
                if(Session::has('copyParentId')){
                    $copyParent = Tr_link_items_search_categories::where('search_category_id', Session::get('copyParentId'))->get();
                    foreach ($copyParent as $item) {
                        //挿入用配列
                        $insItem[] = array(
                                        'item_id'            => $item->item_id,
                                        'search_category_id' => $parentTran['id'],
                                    );
                    }
                }
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                // 親・子の子の処理
                // ~*~*~*~*~*~*~*~*~*~*~*~*~*
                $parentId = $parentTran['id'];
                foreach ($child as $data) {   
                    $childTran[] = DB::transaction(function () use ($data,$parentId) {
                        try {
                            //テーブルに挿入
                            $insert_category = new Tr_search_categories;
                            $insert_category->parent_id         = $parentId;
                            $insert_category->delete_flag       = $data['delete_flag'];
                            $insert_category->name              = $data['name'];
                            $insert_category->parent_sort       = $data['parent_sort'];
                            $insert_category->child_sort        = $data['child_sort'];
                            $insert_category->page_title        = $data['page_title'];
                            $insert_category->page_keywords     = $data['page_keywords'];
                            $insert_category->page_description  = $data['page_description'];
                            $insert_category->save();
                            return['id' => $insert_category->id];
                        } catch (Exception $e) {
                            Log::error($e);
                            abort(400, 'トランザクションが異常終了しました。');
                        }
                    });
                }

                //コピーして登録する案件のカテゴリー情報
                foreach ($request->id as $key => $id) {
                    $copy = Tr_link_items_search_categories::where('search_category_id', $id)->get();
                    foreach ($copy as $item) {
                        //挿入用配列
                        $insItem[] = array(
                                        'item_id'            => $item->item_id,
                                        'search_category_id' => $childTran[$key]['id'],
                                    );
                    }
                }
            }
            // ~*~*~*~*~*~*~*~*~*~*~*~*~*
            // 親のみ、または子のみの処理
            // ~*~*~*~*~*~*~*~*~*~*~*~*~*
            if(!empty($onlyParent) || !empty($onlyChild)) {
                if(!empty($onlyParent)){
                    $data = $onlyParent;
                }elseif(!empty($onlyChild)){
                    $diffParent = array(); 
                    $data = $onlyChild;
                }
                $onlyTran = DB::transaction(function () use ($data) {
                    try {
                        //テーブルに挿入
                        $insert_category                    = new Tr_search_categories;
                        $insert_category->parent_id         = $data['parent_id'];
                        $insert_category->delete_flag       = $data['delete_flag'];
                        $insert_category->name              = $data['name'];
                        $insert_category->parent_sort       = $data['parent_sort'];
                        $insert_category->child_sort        = $data['child_sort'];
                        $insert_category->page_title        = $data['page_title'];
                        $insert_category->page_keywords     = $data['page_keywords'];
                        $insert_category->page_description  = $data['page_description'];
                        $insert_category->save();
                        return[
                                'id'        => $insert_category->id,
                                'parent_id' => $insert_category->parent_id
                            ];
                    } catch (Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });

                //コピーして登録する案件のカテゴリー情報
                $copy = Tr_link_items_search_categories::where('search_category_id', $request->id)->get();
                foreach ($copy as $key => $item) {
                    //挿入用配列
                    $insItem[] = array(
                                    'item_id'            => $item->item_id,
                                    'search_category_id' => $onlyTran['id'],
                                );
                    if($parentFlag){
                        $diffParent[] = $item->item_id;
                    }
                }

                //コピー元と異なる親を選択したとき
                if($parentFlag){
                    foreach ($diffParent as $item) {
                        $checkUpdate = Tr_link_items_search_categories::where('item_id', $item)->where('search_category_id', $onlyTran['parent_id'])->get();
                        if(count($checkUpdate) === 0){
                            //挿入用配列
                            $insItem[] = array(
                                            'item_id'            => $item,
                                            'search_category_id' => $onlyTran['parent_id'],
                                        );
                        }
                    }
                }
            }

            // *****************************************************************
            // カテゴリー表示順更新処理
            // *****************************************************************
            if(!empty($update_db)){
                foreach ($update_db as $update) {
                    //トランザクション
                    DB::transaction(function () use ($update) {
                     try {
                            Tr_search_categories::where('id', $update['id'])->update([
                                'parent_sort' => $update['parent_sort'],
                                'child_sort'  => $update['child_sort']
                            ]);
                        } catch (\Exception $e) {
                            Log::error($e);
                            abort(400, 'トランザクションが異常終了しました。');
                        }
                    });
                }
            }

            // *****************************************************************
            // 案件のカテゴリー挿入処理
            // *****************************************************************
            foreach ($insItem as $data) {
                DB::transaction(function () use ($data) {
                    try {
                        //テーブルに挿入
                        $insert_link_items_search_categories                     = new Tr_link_items_search_categories;
                        $insert_link_items_search_categories->item_id            = $data['item_id'];
                        $insert_link_items_search_categories->search_category_id = $data['search_category_id'];
                        $insert_link_items_search_categories->save();
                    } catch (Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }

        return redirect('/admin/category/search')->with('custom_info_messages','カテゴリー登録は正常に終了しました。');
    }

    /**
     * セッションデータ削除
     * ①登録前
     * ②コピーして登録後一覧画面に遷移したとき
     * ③コピーして登録の親画面で入力、その後子画面の入力途中でもどるボタン2回押下、一覧画面を表示したとき
     * POST:/admin/category/session-forget
     */
    public function ajaxSessionForget(){
        if(Session::has('parentData')){
            Session::forget('parentData');
        }
        if(Session::has('copysChild')){
            Session::forget('copysChild');
        }
        if(Session::has('copysChildNum')){
            Session::forget('copysChildNum');
        }
        if(Session::has('copyParentId')){
            Session::forget('copyParentId');
        }
        if(Session::has('sortMax')){
            Session::forget('sortMax');
        }
        if(Session::has('parents')){
            Session::forget('parents');
        }
        if(Session::has('parentName')){
            Session::forget('parentName');
        }
    }

    /**
     * 編集画面表示
     * GET:/admin/category/modify
     */
    public function showCategoryModify(Request $request){
        //idに紐づいたカテゴリー情報を取得
        $category = Tr_search_categories::where('id', $request->id)->get()->first();

        if(empty($category->parent_id)){
            //親のとき 親カテゴリー最大表示順を取得
            $sortMax = Tr_search_categories::max('parent_sort');
        }else{
            //子のとき
            //親カテゴリーのみを昇順で取得
            $parents = Tr_search_categories::parent()->get();

            //親に対する子カテゴリー最大表示順を取得
            $sortMax = Tr_search_categories::where('parent_sort', $category->parent_sort)
                                                     ->where('child_sort', '!=', null)
                                                     ->max('child_sort');            
        }
        if (empty($category)) {
            abort(404, '指定されたカテゴリーは存在しません。');
        }
        return view('admin.category_modify', compact('category','parents','sortMax'));
    }

    /**
     * 親カテゴリー変更時に表示順を切り替え・親表示順切り替え
     * POST:/admin/category/selectBox
     */
    public function ajaxSelectBox(Request $request){
      
        //親に対する子カテゴリー最大表示順を取得
        $sortMax = Tr_search_categories::where('parent_id', $request->parent_id)
                                                     ->where('child_sort', '!=', null)
                                                     ->max('child_sort');
        //親の表示順                         
        $category = Tr_search_categories::where('id', $request->parent_id)->get()->first();
                                                                                        
        if($request->status == 1){
            //新規登録画面
            $data = [
                        'max'         => $sortMax + 1,
                        'parent_sort' => $category->parent_sort
                    ];
        }else{
            //編集画面
            if($sortMax == 0){
                //子なし親のとき
                $sortMax = 1;
            }
            $data = [
                        'max'         => $sortMax,
                        'parent_sort' => $category->parent_sort
                    ];
        }
        
        //エンコードして返却
        echo json_encode($data);
    }
    

    /**
     * 更新処理
     * POST:/admin/category/modify
     */
    public function updateCategory(CategoryRegistRequest $request){
        
        $update_db[] = array(
                        'id'                => $request->id,
                        'parent_id'         => $request->parent_id,
                        'delete_flag'       => $request->delete_flag,
                        'name'              => $request->category_name,
                        'parent_sort'       => $request->parent_sort,
                        'child_sort'        => $request->child_sort,
                        'page_title'        => $request->page_title,
                        'page_keywords'     => $request->page_keywords,
                        'page_description'  => $request->page_description
                    );
        //編集対象を取得
        $category = Tr_search_categories::where('id', $update_db[0]['id'])->get()->first();

        if(empty($update_db[0]['parent_id'])){
            //親のとき
            //表示順を変更したいとき
            if($update_db[0]['parent_sort'] != $category->parent_sort){
                //編集対象の子どもたちを取得
                $childCategory = Tr_search_categories::where('parent_sort', $category->parent_sort)->get();
                foreach ($childCategory as $update) {
                    $update_db[] = array(
                                    'id'                => $update->id,
                                    'parent_id'         => $update->parent_id,
                                    'delete_flag'       => $update->delete_flag,
                                    'name'              => $update->name,
                                    'parent_sort'       => $update_db[0]['parent_sort'],
                                    'child_sort'        => $update->child_sort,
                                    'page_title'        => $update->page_title,
                                    'page_keywords'     => $update->page_keywords,
                                    'page_description'  => $update->page_description
                                );
                }
                if($update_db[0]['parent_sort'] < $category->parent_sort){
                    //表示順をあげたいとき
                    for($value = $update_db[0]['parent_sort']; $value < $category->parent_sort; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_sort', $value)->get();
                        foreach ($update_category as $update) {
                            $update_db[] = array(
                                            'id'                => $update->id,
                                            'parent_id'         => $update->parent_id,
                                            'delete_flag'       => $update->delete_flag,
                                            'name'              => $update->name,
                                            'parent_sort'       => $value + 1,
                                            'child_sort'        => $update->child_sort,
                                            'page_title'        => $update->page_title,
                                            'page_keywords'     => $update->page_keywords,
                                            'page_description'  => $update->page_description
                                        );
                        }
                    }
                }else{
                    //表示順をさげたいとき
                    $sortNum = $category->parent_sort + 1;
                    for($value = $sortNum; $value <= $update_db[0]['parent_sort']; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_sort', $value)->get();
                        foreach ($update_category as $update) {
                            $update_db[] = array(
                                            'id'                => $update->id,
                                            'parent_id'         => $update->parent_id,
                                            'delete_flag'       => $update->delete_flag,
                                            'name'              => $update->name,
                                            'parent_sort'       => $value - 1,
                                            'child_sort'        => $update->child_sort,
                                            'page_title'        => $update->page_title,
                                            'page_keywords'     => $update->page_keywords,
                                            'page_description'  => $update->page_description
                                        );
                        }
                    }
                }
            }
        }else{
            //子どものとき
            //親を変更したいとき
            if($update_db[0]['parent_id'] != $category->parent_id){

                //編集対象(移動前)の子どもたちを取得
                $childCategory = Tr_search_categories::where('parent_id', $category->parent_id)
                                                        ->where('id', '!=', $request->id)
                                                        ->orderBy('child_sort', 'asc')
                                                        ->get();
                $sortNum = 1;
                //表示順振り直し
                foreach ($childCategory as $update) {
                    $update_db[] = array(
                                    'id'                => $update->id,
                                    'parent_id'         => $update->parent_id,
                                    'delete_flag'       => $update->delete_flag,
                                    'name'              => $update->name,
                                    'parent_sort'       => $update->parent_sort,
                                    'child_sort'        => $sortNum,
                                    'page_title'        => $update->page_title,
                                    'page_keywords'     => $update->page_keywords,
                                    'page_description'  => $update->page_description
                                );
                    $sortNum = $sortNum + 1;
                }

                //移動先親の表示ステータスを確認
                $parentCategory = Tr_search_categories::where('id', $update_db[0]['parent_id'])->get()->first();

                //非表示のとき
                if($parentCategory->delete_flag){
                    $update_db[0]['delete_flag'] = true;
                }
                //移動先親の子ども最大表示順
                $maxSort = Tr_search_categories::where('parent_id', $update_db[0]['parent_id'])->max('child_sort');

                for($value = $update_db[0]['child_sort']; $value < $maxSort + 1; $value++){
                    //編集対象(移動先)の子どもを取得
                    $update_category = Tr_search_categories::where('parent_id', $update_db[0]['parent_id'])
                                                            ->where('child_sort', $value)
                                                            ->get();
      
                    foreach ($update_category as $update) {                              
                        $update_db[] = array(
                                            'id'                =>  $update->id,
                                            'parent_id'         =>  $update->parent_id,
                                            'delete_flag'       =>  $update->delete_flag,
                                            'name'              =>  $update->name,
                                            'parent_sort'       =>  $update->parent_sort,
                                            'child_sort'        =>  $value + 1,
                                            'page_title'        =>  $update->page_title,
                                            'page_keywords'     =>  $update->page_keywords,
                                            'page_description'  =>  $update->page_description
                                        );
                    }
                }
            }

            //表示順を変更したいとき
            if($update_db[0]['parent_id'] == $category->parent_id && $update_db[0]['child_sort'] != $category->child_sort){
                if($update_db[0]['child_sort'] < $category->child_sort){
                    //表示順をあげたいとき
                    for($value = $update_db[0]['child_sort']; $value < $category->child_sort; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_id', $update_db[0]['parent_id'])
                                                                    ->where('parent_sort', $update_db[0]['parent_sort'])
                                                                    ->where('child_sort', $value)
                                                                    ->get()
                                                                    ->first();
                        $update_db[] = array(
                                            'id'                => $update_category->id,
                                            'parent_id'         => $update_category->parent_id,
                                            'delete_flag'       => $update_category->delete_flag,
                                            'name'              => $update_category->name,
                                            'parent_sort'       => $update_category->parent_sort,
                                            'child_sort'        => $value + 1,
                                            'page_title'        => $update_category->page_title,
                                            'page_keywords'     => $update_category->page_keywords,
                                            'page_description'  => $update_category->page_description
                                        );
                    }
                }else{
                    //表示順をさげたいとき
                    $sortNum = $category->child_sort + 1;
                    for($value = $sortNum; $value <= $update_db[0]['child_sort']; $value++){
                        //表示順に紐づいた情報を取得
                        $update_category = Tr_search_categories::where('parent_id', $update_db[0]['parent_id'])
                                                                    ->where('parent_sort', $update_db[0]['parent_sort'])
                                                                    ->where('child_sort', $value)
                                                                    ->get()
                                                                    ->first();
                        $update_db[] = array(
                                            'id'                => $update_category->id,
                                            'parent_id'         => $update_category->parent_id,
                                            'delete_flag'       => $update_category->delete_flag,
                                            'name'              => $update_category->name,
                                            'parent_sort'       => $update_category->parent_sort,
                                            'child_sort'        => $value - 1,
                                            'page_title'        => $update_category->page_title,
                                            'page_keywords'     => $update_category->page_keywords,
                                            'page_description'  => $update_category->page_description
                                        );
                    }
                }
            }
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Tr_search_categories::where('id', $update['id'])->update([
                        // 'path'              => $update['path'],
                        'parent_id'         => $update['parent_id'],
                        'delete_flag'       => $update['delete_flag'],
                        'name'              => $update['name'],
                        'parent_sort'       => $update['parent_sort'],
                        'child_sort'        => $update['child_sort'],     
                        'page_title'        => $update['page_title'],
                        'page_keywords'     => $update['page_keywords'],
                        'page_description'  => $update['page_description'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/category/search')->with('custom_info_messages','カテゴリー更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/category/delete
     */
    public function deleteCategory(Request $request){
        if(empty($request->parent_id)){
            //親のとき
            //表示順の最大値
            $sortMax = Tr_search_categories::max('parent_sort');
            //編集対象
            $update_db[] = array(
                            'id'          => $request->id,
                            'delete_flag' => true,
                            'parent_sort' => $sortMax,
                            'child_sort'  => null,
                        );
            $delete_db[] = array(
                            'parent_id' => $request->id,
                            'child_id'  => 0,
                        );

            //編集対象の子ども
            $update_category = Tr_search_categories::where('parent_sort', $request->parent_sort)
                                            ->where('parent_id', '!=', null)
                                            ->get();

            foreach ($update_category as $update) {
                $update_db[] = array(
                                    'id'          => $update->id,
                                    'delete_flag' => true,
                                    'parent_sort' => $sortMax,
                                    'child_sort'  => $update->child_sort,
                                );
                $delete_db[] = array(
                            'parent_id' => $update->parent_id,
                            'child_id'  => $update->id,
                        );
            }

            $sortMin = $request->parent_sort + 1;
            for($value = $sortMin; $value <= $update_db[0]['parent_sort']; $value++){
                //表示順に紐づいた親情報を取得
                $update_category = Tr_search_categories::where('parent_sort', $value)
                                                        ->where('id', '!=', $request->id)
                                                        ->get();

                foreach ($update_category as $update) {
                    $update_db[] = array(
                                        'id'          => $update->id,
                                        'delete_flag' => $update->delete_flag,
                                        'parent_sort' => $value - 1,
                                        'child_sort'  => $update->child_sort,
                                    );
                }
            }
        }else{
            //子どものとき
            //表示順の最大値
            $sortMax = Tr_search_categories::where('parent_sort', $request->parent_sort)
                                                     ->where('child_sort', '!=', null)
                                                     ->max('child_sort');
            //編集対象
            $update_db[] = array(
                            'id'          => $request->id,
                            'delete_flag' => true,
                            'parent_sort' => $request->parent_sort,
                            'child_sort'  => $sortMax,
                        );
            $delete_db[] = array(
                            'parent_id' => $request->parent_id,
                            'child_id'  => $request->id,
                        );

            $sortMin = $request->child_sort + 1;

            for($value = $sortMin; $value <= $update_db[0]['child_sort']; $value++){
                //表示順に紐づいた情報を取得
                $update_category = Tr_search_categories::where('parent_sort', $request->parent_sort)
                                                            ->where('child_sort', $value)
                                                            ->get()
                                                            ->first();
                $update_db[] = array(
                                    'id'          => $update_category->id,
                                    'delete_flag' => $update_category->delete_flag,
                                    'parent_sort' => $update_category->parent_sort,
                                    'child_sort'  => $value - 1,
                                );
            }
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Tr_search_categories::where('id', $update['id'])->update([
                        'delete_flag' => $update['delete_flag'],
                        'parent_sort' => $update['parent_sort'],
                        'child_sort'  => $update['child_sort'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }

        //削除処理
        foreach ($delete_db as $delete) {
            //トランザクション
            DB::transaction(function () use ($delete) {
             try {
                    Tr_search_categories_display::where('parent_id', $delete['parent_id'])
                                                ->where('child_id', $delete['child_id'])
                                                ->delete();
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
       
        return redirect('/admin/category/search')->with('custom_info_messages','カテゴリー削除は正常に終了しました。');
    }


     /**
     * 論理削除から復活処理
     * GET:/admin/category/insert
     */
    public function insertAgainCategory(Request $request){
        //編集対象
        $update_db[] = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );

        if(empty($request->parent_id)){
            //親のとき
            //編集対象の子ども
            $update_category = Tr_search_categories::where('parent_id', $request->id)->get();

            foreach ($update_category as $update) {
                $update_db[] = array(
                                    'id'          => $update->id,
                                    'delete_flag' => false,
                                );
            }
        }

        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Tr_search_categories::where('id', $update['id'])->update([
                        'delete_flag' => $update['delete_flag'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/category/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }

    /**
     * トップページ表示管理画面
     * GET:/admin/category/list
     */
    public function displayCategorylist(Request $request){
        //親カテゴリ
        $parents = Tr_search_categories::where('parent_id', null)
                                        ->where('delete_flag', false)
                                        ->orderBy('parent_sort', 'asc')
                                        ->get();
        //子カテゴリ
        $children = Tr_search_categories::where('parent_id', '!=', null)
                                        ->where('delete_flag', false)
                                        ->orderBy('child_sort', 'asc')
                                        ->get();
        //表示カテゴリー
        $display_category = Tr_search_categories_display::all();

        return view('/admin/category_display', compact('parents','children','display_category'));
    }

    /**
     * トップページ表示更新処理
     * GET:/admin/category/list
     */
    public function displayUpdateCategory(Request $request){
        $data_db = [];
        if(!empty($request->search_categories)){
            foreach ($request->search_categories as $value) {
                $category = Tr_search_categories::where('id', $value)->get()->first();

                if(empty($category->parent_id)){
                    //親のとき
                    $data_db[] = array(
                                        'parent_id' => $category->id,
                                        'child_id'  => 0,
                                        );
                    //チェック用
                    $parents[] = array('parent_id' => $category->id);
                }else{
                    //子のとき
                    $data_db[] = array(
                                        'parent_id' => $category->parent_id,
                                        'child_id'  => $category->id,
                                        );
                }
            }

            //子に対して親が揃っているかチェック
            $custom_error_messages = [];
            foreach ($data_db as $key => $data) {
                $error[] = array($data);
                if(!empty($parents)){
                    foreach ($parents as $parent) {
                        if($parent["parent_id"] == $data["parent_id"]){
                            unset($error[$key]);
                        }
                    }
                }
            }
            if(!empty($error)){
                array_push($custom_error_messages, '子のみ表示することはできません。子に対応する親のチェックボックスにチェックを入れてください。');
                //フラッシュセッションにエラーメッセージを保存
                \Session::flash('custom_error_messages', $custom_error_messages);
                return back()->withInput();
            }
        }
        //表示カテゴリー
        $display_category = Tr_search_categories_display::all();

        //非表示チェック
        $delete_db = [];
        foreach ($display_category as $key => $display) {
            $delete_db[] = array(
                                'parent_id' => $display->parent_id,
                                'child_id'  => $display->child_id,
                                );

            foreach ($data_db as $data) {
                if($display->parent_id == $data['parent_id']){
                    unset($delete_db[$key]);
                }
            }
        }
        
        //挿入処理
        foreach ($data_db as $data) {
            $display_category = Tr_search_categories_display::where('parent_id', $data['parent_id'])
                                                            ->where('child_id', $data['child_id'])
                                                            ->get()
                                                            ->first();
            if(empty($display_category)){
                //トランザクション
                DB::transaction(function () use ($data) {
                    try {
                        //テーブルに挿入
                        $insert = new Tr_search_categories_display;
                        $insert->parent_id = $data['parent_id'];
                        $insert->child_id = $data['child_id'];
                        $insert->save();

                    } catch (Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        //削除処理
        foreach ($delete_db as $delete) {
            //トランザクション
            DB::transaction(function () use ($delete) {
             try {
                    Tr_search_categories_display::where('parent_id', $delete['parent_id'])
                                                ->where('child_id', $delete['child_id'])
                                                ->delete();
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/category/list')->with('custom_info_messages','処理は正常に終了しました。');
    }
}
