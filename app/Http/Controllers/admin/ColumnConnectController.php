<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_column_connects;
use App\Models\Tr_link_column_connects;
use App\Http\Requests\admin\ColumnConnectRegistRequest;
use App\Libraries\AdminUtility as AdminUtil;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class ColumnConnectController extends AdminController
{
	/**
     * 一覧画面の表示
     * GET:/admin/admin/column-connect/search
     */
    public function showColumnConnectList(Request $request){

        $data_query = [
            'title'       => $request->title ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];


        $query = Tr_column_connects::select('column_connects.*',DB::raw("(GROUP_CONCAT(link_column_connects.keyword SEPARATOR '<br>')) as `keyword`"))
                                        ->leftjoin('link_column_connects', 'column_connects.id','=','link_column_connects.connect_id' )
                                        ->groupBy('column_connects.id');
                                        
                                        
        if(!empty($request->title)){
            //検索「タイトル」に入力したとき
            $query = $query->where('column_connects.title', 'like', '%'.$request->title.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('column_connects.delete_flag', false);
        }
        $connectsList = $query->orderBy('column_connects.id')->paginate(20);

        return view('admin.column_connect_list', compact('connectsList','data_query'));
    }

    /**
     * 新規登録画面の表示
     * GET:/admin/column-connect/input
     */
    public function showColumnConnectInput(){
        return view('admin.column_connect_input');
    }

    /**
     * 新規登録処理
     * POST:/admin/column-connect/input
     */
    public function insertColumnConnect(ColumnConnectRegistRequest $request){

        if(!ctype_digit($request->connect_id)){
            abort(404, '入力された紐付けIDは数値ではありません。');
        }
        $checkConnectId = Tr_link_column_connects::where('connect_id', $request->connect_id)->get();
        if(!empty($checkConnectId)){
            abort(404, '入力された紐付けIDはすでに使われています。');
        }
        $column_connect_data = array(
                        'connect_id'  => $request->connect_id,
                        'title'       => $request->title,
                        'delete_flag' => false,
                    );

        //改行コードを置換してLF改行コードに統一
        $str = str_replace(array("\r\n","\r","\n"), "\n", $request->keyword);
                     
        //LF改行コードで配列に格納
        $link_column_connect_data = explode("\n", $str);

        //挿入処理
        $dbTran = DB::transaction(function () use ($column_connect_data) {
            try {
                //テーブルに挿入
                $insert_column_connect               = new Tr_column_connects;
                $insert_column_connect->connect_id   = $column_connect_data['connect_id'];
                $insert_column_connect->title        = $column_connect_data['title'];
                $insert_column_connect->delete_flag  = $column_connect_data['delete_flag'];
                $insert_column_connect->save();
                return['connect_id' => $insert_column_connect->id];
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        foreach ($link_column_connect_data as $data) {
            DB::transaction(function () use ($data, $dbTran) {
                try {
                    //テーブルに挿入
                    $insert_link_column_connect               = new Tr_link_column_connects;
                    $insert_link_column_connect->connect_id   = $dbTran['connect_id'];
                    $insert_link_column_connect->keyword      = $data;
                    $insert_link_column_connect->save();
                } catch (Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/column-connect/search')->with('custom_info_messages','紐付け登録は正常に終了しました。');
    }

    /**
     * 編集画面の表示
     * GET:/admin/column-connect/modify
     */
    public function showColumnConnectModify(Request $request){
        $connect = Tr_column_connects::select('column_connects.*',DB::raw("(GROUP_CONCAT(link_column_connects.keyword SEPARATOR '\n')) as `keyword`"))
                                        ->leftjoin('link_column_connects', 'column_connects.id','=','link_column_connects.connect_id' )
                                        ->where('id', $request->id)
                                        ->get()
                                        ->first();
                                        
        if (empty($connect)) {
            abort(404, '指定された情報は存在しません。');
        }
        return view('admin.column_connect_modify', compact('connect'));
    }

    /**
     * 更新処理
     * POST:/admin/column-connect/modify
     */
    public function updateColumnConnect(ColumnConnectRegistRequest $request){

        if(!ctype_digit($request->connect_id)){
            abort(404, '入力された紐付けIDは数字ではありません。');
        }

        $column_connect_data = array(
                    'id'        => $request->id,
                    'connect_id'=> $request->connect_id,
                    'title'     => $request->title,
                );
    
        //改行コードを置換してLF改行コードに統一
        $str = str_replace(array("\r\n","\r","\n"), "\n", $request->keyword);
                     
        //LF改行コードで配列に格納
        $keywords = explode("\n", $str);

        foreach ($keywords as $keyword) {
            $link_column_connect_data[] = array(
                            'connect_id'=> $request->id,
                            'keyword'   => $keyword,
                );
        }
        $delete_id = $request->id;

        //更新処理
        DB::transaction(function () use ($column_connect_data) {
            try {
                Tr_column_connects::where('id', $column_connect_data['id'])->update([
                    'connect_id' => $column_connect_data['connect_id'],
                    'title'      => $column_connect_data['title'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        
        //キーワードを削除
        DB::transaction(function () use ($delete_id) {
            try {
                Tr_link_column_connects::where('connect_id', $delete_id)->delete();
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        //キーワードを挿入
        foreach ($link_column_connect_data as $data) {
            DB::transaction(function () use ($data) {
                try {
                    //テーブルに挿入
                    $insert_link_column_connect               = new Tr_link_column_connects;
                    $insert_link_column_connect->connect_id   = $data['connect_id'];
                    $insert_link_column_connect->keyword      = $data['keyword'];
                    $insert_link_column_connect->save();
                } catch (Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/column-connect/search')->with('custom_info_messages','紐付け更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/column-connect/delete
     */
    public function deleteColumnConnect(Request $request){

        $update = array(
                    'id'          => $request->id,
                    'delete_flag' => true,
                );

        //トランザクション
        DB::transaction(function () use ($update) {
         try {
                Tr_column_connects::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/column-connect/search')->with('custom_info_messages','紐付け削除は正常に終了しました。');
    }
     /**
     * 論理削除から復活処理
     * GET:/admin/column-connect/insert
     */
    public function insertAgainColumnConnect(Request $request){

        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );

        DB::transaction(function () use ($update) {
            try {
                Tr_column_connects::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/column-connect/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }
 }

