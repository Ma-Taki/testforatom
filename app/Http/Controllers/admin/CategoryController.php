<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Tr_search_categories;
use App\Models\Tr_search_categories_display;
use App\Http\Requests\admin\CategoryRegistRequest;
use App\Libraries\AdminUtility as AdminUtil;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

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
