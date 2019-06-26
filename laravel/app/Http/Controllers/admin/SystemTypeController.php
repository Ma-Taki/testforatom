<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Libraries\AdminUtility as AdminUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Ms_sys_types;
use App\Http\Requests\admin\SystemTypeRegistRequest;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class SystemTypeController extends AdminController
{
    /**
     * 検索一覧画面・処理
     * GET,POST:/admin/system-type/search
     */
    public function searchSystemType(Request $request){
        $data_query = [
            'name'        => $request->name ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];

        $query = Ms_sys_types::select('sys_types.*');
                                                    
        if(!empty($request->name)){
            //検索「システム種別名」に入力したとき
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $sysTypes = $query->orderBy('sort_order', 'asc')->paginate(20);

        return view('admin.system_type_list',compact('sysTypes','data_query'));
    }
    
    /**
     * 新規登録画面表示
     * GET:/admin/system-type/input
     */
    public function showSystemTypeInput(){
        //最大表示順値を取得
        $sortMax = Ms_sys_types::max('sort_order');
        $sortMax = $sortMax + 1;
        return view('admin.system_type_input', compact('sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/system-type/input
     */
    public function insertSystemType(SystemTypeRegistRequest $request){
        
        $data = array(
                        'name'        => $request->sysType_name,
                        'delete_flag' => false,
                        'sort_order'  => $request->sort_order,
                    );

        //表示順の最大値
        $sortMax = Ms_sys_types::max('sort_order');
        for($value = $data['sort_order']; $value <= $sortMax; $value++){
            //更新したい情報を取得
            $update_jobType = Ms_sys_types::where('sort_order', $value)->first();
            $update_db[] = array(
                        'id'         => $update_jobType->id,
                        'sort_order' => $value + 1,
                    );
        }

        //挿入処理
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_sysType               = new Ms_sys_types;
                $insert_sysType->name         = $data['name'];
                $insert_sysType->delete_flag  = $data['delete_flag'];
                $insert_sysType->sort_order   = $data['sort_order'];
                $insert_sysType->save();
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
                        Ms_sys_types::where('id', $update['id'])->update([
                            'sort_order' => $update['sort_order']
                        ]); 
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        return redirect('/admin/system-type/search')->with('custom_info_messages','システム種別登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/system-type/modify
     */
    public function showSystemTypeModify(Request $request){
        $sysType = Ms_sys_types::where('id', $request->id)->get()->first();
        $sortMax = Ms_sys_types::max('sort_order');

        if (empty($sysType)) {
            abort(404, '指定されたシステム種別は存在しません。');
        }
        return view('admin.system_type_modify', compact('sysType','sortMax'));
    }

    /**
     * 更新処理
     * POST:/admin/system-type/modify
     */
    public function updateSystemType(SystemTypeRegistRequest $request){
        
        $update_db[] = array(
                    'id'        => $request->id,
                    'name'      => $request->sysType_name,
                    'sort_order'=> $request->sort_order,
                );

        //編集対象を取得
        $sysType = Ms_sys_types::where('id', $update_db[0]['id'])->get()->first();

        //表示順を変更したいとき
        if($update_db[0]['sort_order'] !== $sysType->sort_order){
            if($update_db[0]['sort_order'] < $sysType->sort_order){
                //表示順をあげたいとき
                for($value = $update_db[0]['sort_order']; $value < $sysType->sort_order; $value++){
                    //表示順に紐づいた情報を取得
                    $update_sysType = Ms_sys_types::where('sort_order', $value)->first();
                    $update_db[] = array(
                                        'id'          => $update_sysType->id,
                                        'name'        => $update_sysType->name,
                                        'sort_order'  => $value + 1,
                                    );
                }
            }else{
                //表示順をさげたいとき
                $sortNum = $sysType->sort_order + 1;
                for($value = $sortNum; $value <= $update_db[0]['sort_order']; $value++){
                    //表示順に紐づいた情報を取得
                    $update_sysType = Ms_sys_types::where('sort_order', $value)->get()->first();
                    $update_db[] = array(
                                        'id'          => $update_sysType->id,
                                        'name'        => $update_sysType->name,
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
                    Ms_sys_types::where('id', $update['id'])->update([
                        'name'       => $update['name'],
                        'sort_order' => $update['sort_order'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/system-type/search')->with('custom_info_messages','システム種別更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/system-type/delete
     */
    public function deleteSystemType(Request $request){
        //表示順の最大値
        $sortMax = Ms_sys_types::max('sort_order');
        $update_db[] = array(
                    'id'          => $request->id,
                    'sort_order'  => $sortMax,
                    'delete_flag' => true,
                );
        $sortMin = $request->sort_order + 1;
        for($value = $sortMin; $value <= $update_db[0]['sort_order']; $value++){
            //表示順に紐づいた情報を取得
            $update_sysType = Ms_sys_types::where('sort_order', $value)->get()->first();

            $update_db[] = array(
                                'id'          => $update_sysType->id,
                                'sort_order'  => $value - 1,
                                'delete_flag' => $update_sysType->delete_flag,
                            );
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Ms_sys_types::where('id', $update['id'])->update([
                        'sort_order'  => $update['sort_order'],
                        'delete_flag' => $update['delete_flag'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/system-type/search')->with('custom_info_messages','システム種別削除は正常に終了しました。');
    }

     /**
     * 論理削除から復活処理
     * GET:/admin/system-type/insert
     */
    public function insertAgainSystemType(Request $request){
        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );
        
        DB::transaction(function () use ($update) {
            try {
                Ms_sys_types::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/system-type/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }
}
