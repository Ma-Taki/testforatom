<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_slide_images;
use App\Http\Requests\admin\SlideImgRegistRequest;
use App\Http\Requests\admin\SlideImgEditRequest;
use App\Libraries\AdminUtility as AdminUtil;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class SlideController extends AdminController
{
	/**
     * 一覧画面の表示
     * GET:/admin/slide/list
     */
    public function showSlideList(){
        return view('admin.slide_list');
    }

    /**
     * 新規登録画面の表示
     * GET:/admin/slide/input
     */
    public function index(){
        //最大表示順値を取得
        $sortMax = Tr_slide_images::max('sort_order');
        $sortMax = $sortMax + 1;
        return view('admin.slide_input', compact('sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/slide/input
     */
    public function store(SlideImgRegistRequest $request){

        $file = !empty($request->image_file) ? $request->image_file : null;
        $original_name = null;

        //▽▽▽ 画像アップロード時のバリデーション ▽▽▽
        if(!empty($file)) {
            $custom_error_messages = [];
        
            //拡張子チェック
            $original_name = collect(explode('.', $file->getClientOriginalName()));
            if ($original_name->count() != 2 || !in_array($original_name->last(), AdminUtil::FILE_UPLOAD_RULE['allowedExtensions'])) {
                    array_push($custom_error_messages, '画像の拡張子が正しくありません。.jpgの画像をアップロードしてください。');
            }

            //mimeTypeチェック
            $mime_type = $file->getClientMimeType();
            if (!in_array($mime_type, AdminUtil::FILE_UPLOAD_RULE['allowedTypes'])) {
                    array_push($custom_error_messages, '画像のファイル形式が正しくありません。');
            }

            if (!empty($custom_error_messages)) {
                //フラッシュセッションにエラーメッセージを保存
                \Session::flash('custom_error_messages', $custom_error_messages);
                return back()->withInput();
            }
        }
        // △△△ 画像アップロード時のバリデーション △△△
        
        $data_db[] = array(
                        'title'      => $request->image_title,
                        'link'       => $request->image_link,
                        'sort_order' => $request->image_sort,
                    );

        //表示順の最大値
        $sortMax = Tr_slide_images::max('sort_order');
        for($value = $data_db[0]['sort_order']; $value <= $sortMax; $value++){
            //更新したい画像情報を取得
            $update_image = Tr_slide_images::where('sort_order', $value)->first();
            $data_db[] = array(
                        'id'         => $update_image->id,
                        'sort_order' => $value + 1,
                    );
        }

        //挿入・更新処理
        foreach ($data_db as $key => $data) {
            if($key == 0){
                //トランザクション
                $dbTran = DB::transaction(function () use ($data) {
                    try {
                        //テーブルに挿入
                        $insert_image = new Tr_slide_images;
                        $insert_image->title       = $data['title'];
                        $insert_image->link        = $data['link'];
                        $insert_image->sort_order  = $data['sort_order'];
                        $insert_image->delete_flag = false;
                        $insert_image->save();

                        return['file_name' => $insert_image->id.'.jpg'];
                    } catch (Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }else{
                //トランザクション
                DB::transaction(function () use ($data) {
                    try {
                        Tr_slide_images::where('id', $data['id'])->update([
                            'sort_order' => $data['sort_order'],
                        ]); 
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        //ファイルをローカルに保存
        if(!empty($file)) {
            $file->move(base_path().'/public/front/images/slide',$dbTran['file_name']);
        }
        return redirect('/admin/slide/list')->with('custom_info_messages','画像登録は正常に終了しました。');
    }

    /**
     * 編集画面の表示
     * GET:/admin/slide/modify
     */
    public function showSlideModify(Request $request){
        //編集対象画像を取得
        $image = Tr_slide_images::where('id', $request->id)->get()->first();
        if (empty($image)) {
            abort(404, '指定された画像情報は存在しません。');
        }
        //最大表示順値を取得
        $sortMax = Tr_slide_images::max('sort_order');
        return view('admin.slide_modify', compact('image','sortMax'));
    }

    /**
     * 更新処理
     * POST:/admin/slide/modify
     */
    public function updateAdminSlide(SlideImgEditRequest $request){

        //画像を変更したいとき
        if(!empty($request->image_file)){
           
            $file = !empty($request->image_file) ? $request->image_file : null;
            $original_name = null;

            //▽▽▽ 画像アップロード時のバリデーション ▽▽▽
            if(!empty($file)) {
                $custom_error_messages = [];
            
                //拡張子チェック
                $original_name = collect(explode('.', $file->getClientOriginalName()));
                if ($original_name->count() != 2 || !in_array($original_name->last(), AdminUtil::FILE_UPLOAD_RULE['allowedExtensions'])) {
                        array_push($custom_error_messages, '画像の拡張子が正しくありません。.jpgの画像をアップロードしてください。');
                }

                //mimeTypeチェック
                $mime_type = $file->getClientMimeType();
                if (!in_array($mime_type, AdminUtil::FILE_UPLOAD_RULE['allowedTypes'])) {
                        array_push($custom_error_messages, '画像のファイル形式が正しくありません。');
                }

                if (!empty($custom_error_messages)) {
                    //フラッシュセッションにエラーメッセージを保存
                    \Session::flash('custom_error_messages', $custom_error_messages);
                    return back()->withInput();
                }
            }
            // △△△ 画像アップロード時のバリデーション △△△
        }
        $update_db[] = array(
                            'id'        => $request->id,
                            'title'     => $request->image_title,
                            'link'      => $request->image_link,
                            'sort_order'=> $request->image_sort,
                        );

        //編集対象画像を取得
        $image = Tr_slide_images::where('id', $update_db[0]['id'])->get()->first();

        //表示順を変更したいとき
        if($update_db[0]['sort_order'] !== $image->sort_order){
            if($update_db[0]['sort_order'] < $image->sort_order){
                //表示順をあげたいとき
                for($value = $update_db[0]['sort_order']; $value < $image->sort_order; $value++){
                    //表示順に紐づいた画像情報を取得
                    $update_image = Tr_slide_images::where('sort_order', $value)->first();
                    $update_db[] = array(
                                        'id'          => $update_image->id,
                                        'title'       => $update_image->title,
                                        'link'        => $update_image->link,
                                        'sort_order'  => $value + 1,
                                    );
                }
            }else{
                //表示順をさげたいとき
                $sortNum = $image->sort_order + 1;
                for($value = $sortNum; $value <= $update_db[0]['sort_order']; $value++){
                    //表示順に紐づいた画像情報を取得
                    $update_image = Tr_slide_images::where('sort_order', $value)->get()->first();
                    $update_db[] = array(
                                        'id'          => $update_image->id,
                                        'title'       => $update_image->title,
                                        'link'        => $update_image->link,
                                        'sort_order'  => $value - 1,
                                    );
                }
            }
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Tr_slide_images::where('id', $update['id'])->update([
                        'title'      => $update['title'],
                        'link'       => $update['link'],
                        'sort_order' => $update['sort_order'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        //ファイルをローカルに保存
        if(!empty($file)) {
            $file->move(base_path().'/public/front/images/slide',$update_db[0]['id'].'.jpg');
            //キャッシュを取得後に削除
            Cache::pull($request->id);
        }
        return redirect('/admin/slide/list')->with('custom_info_messages','編集処理は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/slide/delete
     */
    public function deleteAdminSlide(Request $request){
        //表示ステータスが有効なものをカウント
        $count = Tr_slide_images::where('delete_flag', 'false')->count();
        $messages = 'ステータス有効の画像が3枚以上ないときは削除できません。';

        //表示ステータス画像が3枚以上あるとき
        if($count >= 3){           
            //表示順の最大値
            $sortMax = Tr_slide_images::max('sort_order');
            $update_db[] = array(
                        'id'          => $request->id,
                        'sort_order'  => $sortMax,
                        'delete_flag' => true,
                    );
            $messages = '画像削除は正常に終了しました。';
            $sortMin = $request->sort_order + 1;

            for($value = $sortMin; $value <= $update_db[0]['sort_order']; $value++){
                //表示順に紐づいた賀状情報を取得
                $update_image = Tr_slide_images::where('sort_order', $value)->get()->first();
                $update_db[] = array(
                                    'id'          => $update_image->id,
                                    'sort_order'  => $value - 1,
                                    'delete_flag' => $update_image->delete_flag,
                                );
            }

            //更新処理
            foreach ($update_db as $update) {
                //トランザクション
                DB::transaction(function () use ($update) {
                 try {
                        Tr_slide_images::where('id', $update['id'])->update([
                            'sort_order'  => $update['sort_order'],
                            'delete_flag' => $update['delete_flag'],
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        return redirect('/admin/slide/list')->with('custom_info_messages',$messages);
    }

     /**
     * 論理削除から復活処理
     * GET:/admin/slide/insert
     */
    public function insertAdminSlide(Request $request){

        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );

        DB::transaction(function () use ($update) {
            try {
                Tr_slide_images::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/slide/list')->with('custom_info_messages','復活処理は正常に終了しました。');
    }
}
?>
