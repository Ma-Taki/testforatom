<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Libraries\AdminUtility as AdminUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Ms_job_types;
use App\Http\Requests\admin\PositionRegistRequest;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class PositionController extends AdminController
{
    /**
     * 検索一覧画面・処理
     * GET,POST:/admin/position/search
     */
    public function searchPosition(Request $request){
    
        $data_query = [
            'name'        => $request->name ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];

        $query = Ms_job_types::select('job_types.*');
                                                    
        if(!empty($request->name)){
            //検索「ポジション名」に入力したとき
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $jobTypes = $query->orderBy('sort_order', 'asc')->paginate(20);
                                                             
        return view('admin.position_list',compact('jobTypes','data_query'));
    }

    /**
     * 新規登録画面表示
     * GET:/admin/position/input
     */
    public function showPositionInput(){
        //最大表示順値を取得
        $sortMax = Ms_job_types::max('sort_order');
        $sortMax = $sortMax + 1;
        return view('admin.position_input', compact('sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/position/input
     */
    public function insertPosition(PositionRegistRequest $request){
        
        $data = array(
                        'name'        => $request->position_name,
                        'delete_flag' => false,
                        'sort_order'  => $request->sort_order,
                    );

        //表示順の最大値
        $sortMax = Ms_job_types::max('sort_order');
        for($value = $data['sort_order']; $value <= $sortMax; $value++){
            //更新したい情報を取得
            $update_jobType = Ms_job_types::where('sort_order', $value)->first();
            $update_db[] = array(
                        'id'         => $update_jobType->id,
                        'sort_order' => $value + 1,
                    );
        }

        //挿入処理
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_jobType               = new Ms_job_types;
                $insert_jobType->name         = $data['name'];
                $insert_jobType->delete_flag  = $data['delete_flag'];
                $insert_jobType->sort_order   = $data['sort_order'];
                $insert_jobType->save();
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        //更新処理
        if(!empty($update_db)){
            foreach($update_db as $update){
                //トランザクション
                DB::transaction(function () use ($update) {
                    try {
                        Ms_job_types::where('id', $update['id'])->update([
                            'sort_order' => $update['sort_order']
                        ]); 
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        return redirect('/admin/position/search')->with('custom_info_messages','カテゴリー登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/position/modify
     */
    public function showPositionModify(Request $request){

        $jobType = Ms_job_types::where('id', $request->id)->get()->first();
        $sortMax = Ms_job_types::max('sort_order');

        if (empty($jobType)) {
            abort(404, '指定されたポジションは存在しません。');
        }
        return view('admin.position_modify', compact('jobType','sortMax'));
    }    

    /**
     * 更新処理
     * POST:/admin/position/modify
     */
    public function updatePosition(PositionRegistRequest $request){
        
        $update_db[] = array(
                    'id'        => $request->id,
                    'name'      => $request->position_name,
                    'sort_order'=> $request->sort_order,
                );

        //編集対象を取得
        $jobType = Ms_job_types::where('id', $update_db[0]['id'])->get()->first();

        //表示順を変更したいとき
        if($update_db[0]['sort_order'] !== $jobType->sort_order){
            if($update_db[0]['sort_order'] < $jobType->sort_order){
                //表示順をあげたいとき
                for($value = $update_db[0]['sort_order']; $value < $jobType->sort_order; $value++){
                    //表示順に紐づいた情報を取得
                    $update_jobType = Ms_job_types::where('sort_order', $value)->first();
                    $update_db[] = array(
                                        'id'          => $update_jobType->id,
                                        'name'        => $update_jobType->name,
                                        'sort_order'  => $value + 1,
                                    );
                }
            }else{
                //表示順をさげたいとき
                $sortNum = $jobType->sort_order + 1;
                for($value = $sortNum; $value <= $update_db[0]['sort_order']; $value++){
                    //表示順に紐づいた情報を取得
                    $update_jobType = Ms_job_types::where('sort_order', $value)->get()->first();
                    $update_db[] = array(
                                        'id'          => $update_jobType->id,
                                        'name'        => $update_jobType->name,
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
                    Ms_job_types::where('id', $update['id'])->update([
                        'name'       => $update['name'],
                        'sort_order' => $update['sort_order'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/position/search')->with('custom_info_messages','ポジション更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/position/delete
     */
    public function deletePosition(Request $request){
          
        //表示順の最大値
        $sortMax = Ms_job_types::max('sort_order');
        $update_db[] = array(
                    'id'          => $request->id,
                    'sort_order'  => $sortMax,
                    'delete_flag' => true,
                );
        $sortMin = $request->sort_order + 1;
        for($value = $sortMin; $value <= $update_db[0]['sort_order']; $value++){
            //表示順に紐づいた情報を取得
            $update_jobType = Ms_job_types::where('sort_order', $value)->get()->first();

            $update_db[] = array(
                                'id'          => $update_jobType->id,
                                'sort_order'  => $value - 1,
                                'delete_flag' => $update_jobType->delete_flag,
                            );
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Ms_job_types::where('id', $update['id'])->update([
                        'sort_order'  => $update['sort_order'],
                        'delete_flag' => $update['delete_flag'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/position/search')->with('custom_info_messages','ポジション削除は正常に終了しました。');
    }


     /**
     * 論理削除から復活処理
     * GET:/admin/position/insert
     */
    public function insertAgainPosition(Request $request){

        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );
        
        DB::transaction(function () use ($update) {
            try {
                Ms_job_types::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/position/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }
}
