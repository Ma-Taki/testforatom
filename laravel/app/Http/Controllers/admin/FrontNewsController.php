<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_front_news;
use App\Libraries\AdminUtility as AdminUtil;
use App\Http\Requests\admin\FrontNewsRegistRequest;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class FrontNewsController extends AdminController
{
	/**
     * 一覧画面表示
     * GET:/admin/front-news/search
     */
    public function showFrontNews(Request $request){
        $data_query = [
            'title'        => $request->title ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];

        $query = Tr_front_news::select('front_news.*');
                                                    
        if(!empty($request->title)){
            //検索「タイトル」に入力したとき
            $query = $query->where('title', 'like', '%'.$request->title.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $newsList = $query->orderBy('release_date', 'DESC')->paginate(20);
                                                                                   
        return view('admin.front_news_list',compact('newsList','data_query'));
    }

    /**
     * 新規登録画面表示
     * GET:/admin/front-news/input
     */
    public function showFrontNewsInput(){
        return view('admin.front_news_input');
    }

    /**
     * 挿入処理
     * GET:/admin/front-news/input
     */
    public function insertFrontNews(FrontNewsRegistRequest $request){
        
        $data = array(
                        'release_date'  => $request->release_date,
                        'title'         => $request->title,
                        'contents'      => $request->contents,
                        'delete_flag'   => false
                    );

        //挿入処理
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_front_news               = new Tr_front_news;
                $insert_front_news->release_date = date('Y-m-d', strtotime($data['release_date']));
                $insert_front_news->title        = $data['title'];
                $insert_front_news->contents     = $data['contents'];
                $insert_front_news->delete_flag  = $data['delete_flag'];
                $insert_front_news->save();
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/front-news/search')->with('custom_info_messages','お知らせ登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/front-news/modify
     */
    public function showFrontNewsModify(Request $request){
        $news = Tr_front_news::where('id', $request->id)->get()->first();
        if (empty($news)) {
            abort(404, '指定されたお知らせは存在しません。');
        }
        return view('admin.front_news_modify', compact('news'));
    }

	/**
     * 更新処理
     * GET:/admin/front-news/update
     */
    public function updateFrontNews(FrontNewsRegistRequest $request){
            
        $update = array(
                        'id'            => $request->id,
                        'release_date'  => $request->release_date,
                        'title'         => $request->title,
                        'contents'      => $request->contents,
                    );

        //更新処理
        DB::transaction(function () use ($update) {
         try {
                Tr_front_news::where('id', $update['id'])->update([
                    'release_date'  => $update['release_date'],
                    'title'         => $update['title'],
                    'contents'      => $update['contents']
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        
        return redirect('/admin/front-news/search')->with('custom_info_messages','お知らせ更新は正常に終了しました。');
    }

    /**
     * 削除処理
     * GET:/admin/front-news/delete
     */
    public function deleteFrontNews(Request $request){

        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => true
                    );

        //更新処理
        DB::transaction(function () use ($update) {
         try {
                Tr_front_news::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag']
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/front-news/search')->with('custom_info_messages','お知らせ削除は正常に終了しました。');
    }

    /**
     * 復活処理
     * GET:/admin/front-news/insert
     */
    public function insertAgainFrontNews(Request $request){

        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );
        
        DB::transaction(function () use ($update) {
            try {
                Tr_front_news::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/front-news/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }    
}
?>
