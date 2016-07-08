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

        // 管理者名
        $admin_name = $request->input('admin_name');
        // ログインID
        $login_id = $request->input('login_id');
        // パスワード
        $password = md5($request->input('password', ""));
        // 権限
        $authList = $request->input('auths');
        // 現在時刻
        $timestamp = time();

        // トランザクション
        DB::transaction(function () use ($admin_name, $login_id, $password, $authList, $timestamp) {
            try {
                // 管理者テーブルにインサート
                $user = Tr_admin_user::create([
                    'admin_name' => $admin_name,
                    'login_id' => $login_id,
                    'password' => $password,
                    'registration_date' => date('Y-m-d H:i:s', $timestamp),
                    'last_update_date' => date('Y-m-d H:i:s', $timestamp),
                    'last_login_date' => date('Y-m-d H:i:s', $timestamp),
                    'delete_flag' => false,
                ]);

                // 管理者権限中間テーブルにインサート
                foreach ((array)$authList as $auth) {
                    Tr_link_admin_user_admin_auth::create([
                        'admin_id' => $user->id,
                        'auth_id' => $auth,
                    ]);
                }
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // トランザクション正常終了
        return redirect('/admin/user/list')->with('custom_info_messages','ユーザ登録は正常に終了しました。');
    }

    /**
     * 編集画面の表示
     * GET:/admin/user/modify
     */
    public function showUserModify(Request $request){

        // 管理者ID
        $admin_id = $request->input('id');

        // 編集対象ユーザを取得
        $user = Tr_admin_user::where('id', $admin_id)->get()->first();
        if (empty($user)) {
            abort(404, '指定されたユーザは存在しません。');
        } elseif ($user->delete_flag || $user->delete_date != null) {
            abort(404, '指定されたユーザは既に削除されています。');
        }

        // 編集対象のユーザがマスター管理であるか
        $master_flg = false;

        $authList = array();
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

        // 管理者ID
        $admin_id = $request->input('admin_id');
        // 管理者名
        $admin_name = $request->input('admin_name');
        // ログインID
        $login_id = $request->input('login_id');
        // パスワード
        $password = $request->input('password');
        if (!empty($password)) $password = md5($password);
        // 権限リスト
        $authList = explode(',', $request->input('postAuths'));
        // 現在時刻
        $timestamp = time();
        // 編集対象がマスター管理者かどうか
        $master_flg = $request->input('master_flg');

        // トランザクション
        DB::transaction(function () use ($admin_id, $admin_name, $login_id, $password, $authList, $timestamp, $master_flg) {
            try {
                // 管理者テーブルをアップデート
                if (!empty($password)) {
                    Tr_admin_user::where('id', $admin_id)->update([
                        'admin_name' => $admin_name,
                        'login_id' => $login_id,
                        'password' => $password,
                        'last_update_date' => date('Y-m-d H:i:s', $timestamp),
                    ]);
                } else {
                    // passwordは変更がない場合パラメータに含まれないため対象外とする
                    Tr_admin_user::where('id', $admin_id)->update([
                        'admin_name' => $admin_name,
                        'login_id' => $login_id,
                        'last_update_date' => date('Y-m-d H:i:s', $timestamp),
                    ]);
                }

                // 編集者：マスター管理者　かつ　編集対象：一般管理者の場合のみ権限の更新を行う
                if (session(ssnUtil::SESSION_KEY_MASTER_FLG) && !$master_flg) {
                    // 管理者権限中間テーブルをデリートインサート
                    Tr_link_admin_user_admin_auth::where('admin_id', $admin_id)->delete();
                    foreach ((array)$authList as $auth) {
                        Tr_link_admin_user_admin_auth::create([
                            'admin_id' => $admin_id,
                            'auth_id' => $auth,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // 自身の管理者名を更新した場合、sessionに上書き保存する
        if ($admin_id == session(ssnUtil::SESSION_KEY_ADMIN_ID)) {
            session([ssnUtil::SESSION_KEY_ADMIN_NAME => $admin_name]);
        }

        // トランザクション正常終了時のリダイレクト先
        $redirectPath = '/admin/top';
        if (session(ssnUtil::SESSION_KEY_MASTER_FLG)) {
            // マスター管理者はユーザ一覧へ
            $redirectPath = '/admin/user/list';
        }

        return redirect($redirectPath)->with('custom_info_messages','ユーザ更新は正常に終了しました。');
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
        } elseif (parent::isExistAuth($admin_id, mdlUtil::AUTH_TYPE_MASTER)) {
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
