<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\admin\AdminUserRegistRequest;
use App\Http\Requests\admin\AdminUserEditRequest;
use App\Http\Controllers\AdminController;
use App\Models\Tr_admin_user;
use App\Models\Ms_admin_auth;
use App\Models\Tr_link_admin_user_admin_auth;
use App\Libraries\SessionUtility as ssnUtil;
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
use DB;
use Carbon\Carbon;
use Log;

class UserController extends AdminController
{
    /**
     * 一覧画面の表示
     * GET:/admin/user/list
     */
    public function showUserList(){
        $userList = Tr_admin_user::all();
        return view('admin.user_list', compact('userList'));
    }

    /**
     * 新規登録画面の表示
     * GET:/admin/user/input
     */
    public function showUserInput(){
        return view('admin.user_input');
    }

    /**
     * 新規登録処理
     * POST:/admin/user/input
     */
    public function insertAdminUser(AdminUserRegistRequest $request){

        $data_db = [
            'admin_name' => $request->admin_name,
            'login_id'   => $request->login_id,
            'password'   => md5($request->password ?: ""),
            'authList'   => $request->auths,
            'now'        => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        // トランザクション
        DB::transaction(function () use ($data_db) {
            try {
                // 管理者テーブルにインサート
                $user = Tr_admin_user::create([
                    'admin_name'        => $data_db['admin_name'],
                    'login_id'          => $data_db['login_id'],
                    'password'          => $data_db['password'],
                    'registration_date' => $data_db['now'],
                    'last_update_date'  => $data_db['now'],
                    'last_login_date'   => $data_db['now'],
                    'delete_flag'       => false,
                ]);

                // 管理者権限中間テーブルにインサート
                foreach ((array)$data_db['authList'] as $auth) {
                    Tr_link_admin_user_admin_auth::create([
                        'admin_id' => $user->id,
                        'auth_id'  => $auth,
                    ]);
                }

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // 正常終了
        return redirect('/admin/user/list')->with('custom_info_messages','ユーザ登録は正常に終了しました。');
    }

    /**
     * 編集画面の表示
     * GET:/admin/user/modify
     */
    public function showUserModify(Request $request){

        // 編集対象ユーザを取得
        $user = Tr_admin_user::where('id', $request->id)->get()->first();
        if (empty($user)) {
            abort(404, '指定されたユーザは存在しません。');
        } elseif ($user->delete_flag || $user->delete_date != null) {
            abort(404, '指定されたユーザは既に削除されています。');
        }

        // 編集対象のユーザの属性 マスター or 一般
        $master_flg = false;
        $authList = [];
        foreach ($user->auths as $auth) {
            if ($auth->auth_name.'.'.$auth->auth_type === mdlUtil::AUTH_TYPE_MASTER) {
                $master_flg = true;
            }
            array_push($authList, $auth->id);
        }

        return view('admin.user_modify', compact(
            'user',
            'authList',
            'master_flg'));
    }

    /**
     * 更新処理
     * POST:/admin/user/modify
     */
    public function updateAdminUser(AdminUserEditRequest $request){

        $data_db = [
            'admin_id'   => $request->id,
            'admin_name' => $request->admin_name,
            'login_id'   => $request->login_id,
            'password'   => $request->password ? md5($request->password) : '',
            'auth_list'  => $request->auths,
            'now'        => Carbon::now()->format('Y-m-d H:i:s'),
            'master_flg' => $request->master_flg,
        ];

        // トランザクション
        DB::transaction(function () use ($data_db) {
            try {
                // 管理者テーブルをアップデート
                if (!empty($data_db['password'])) {
                    Tr_admin_user::where('id', $data_db['admin_id'])->update([
                        'admin_name'       => $data_db['admin_name'],
                        'login_id'         => $data_db['login_id'],
                        'password'         => $data_db['password'],
                        'last_update_date' => $data_db['now'],
                    ]);
                } else {
                    // passwordは変更がない場合入力されないため対象外とする
                    Tr_admin_user::where('id', $data_db['admin_id'])->update([
                        'admin_name'       => $data_db['admin_name'],
                        'login_id'         => $data_db['login_id'],
                        'last_update_date' => $data_db['now'],
                    ]);
                }

                // 編集者：マスター管理者　かつ　編集対象：一般管理者の場合のみ権限の更新を行う
                if (session(ssnUtil::SESSION_KEY_MASTER_FLG) && !$data_db['master_flg']) {
                    // 管理者権限中間テーブルをデリートインサート
                    Tr_link_admin_user_admin_auth::where('admin_id', $data_db['admin_id'])->delete();
                    foreach ((array)$data_db['auth_list'] as $auth) {
                        Tr_link_admin_user_admin_auth::create([
                            'admin_id' => $data_db['admin_id'],
                            'auth_id'  => $auth,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // 自身の管理者名を更新した場合、sessionに上書き保存する
        if ($request->admin_id == session(ssnUtil::SESSION_KEY_ADMIN_ID)) {
            session([ssnUtil::SESSION_KEY_ADMIN_NAME => $request->admin_name]);
        }

        // トランザクション正常終了時のリダイレクト先
        $redirect_path = '/admin/top';
        if (session(ssnUtil::SESSION_KEY_MASTER_FLG)) {
            $redirect_path = '/admin/user/list';
        }
        return redirect($redirect_path)->with('custom_info_messages','ユーザ更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/user/delete
     */
    public function deleteAdminUser(Request $request){

        // 管理者ID
        $admin_id = $request->input('id');

        // 現在時刻
        $timestamp = time();

        // 削除対象ユーザを取得
        $user = Tr_admin_user::where('id', $admin_id)->get()->first();
        if (empty($user)) {
            abort(404, '指定されたユーザは存在しません。');
        } elseif ($user->delete_flag || $user->delete_date != null) {
            abort(404, '指定されたユーザは既に削除されています。');
        } elseif (admnUtil::isExistAuthById($admin_id, mdlUtil::AUTH_TYPE_MASTER)) {
            abort(400, 'マスター管理者は削除できません。');
        }

        // トランザクション
        DB::transaction(function () use ($admin_id, $timestamp) {
            try {
                // 管理者テーブルをアップデート
                Tr_admin_user::where('id', $admin_id)->update([
                    'last_update_date' => date('Y-m-d H:i:s', $timestamp),
                    'delete_flag' => true,
                    'delete_date' => date('Y-m-d H:i:s', $timestamp),
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/user/list')->with('custom_info_messages','ユーザ削除は正常に終了しました。');
    }
}
