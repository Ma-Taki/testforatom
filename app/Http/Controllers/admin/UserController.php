<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\admin\AdminUserRequest;

use App\Http\Controllers\AdminController;
use App\Models\Tr_admin_user;
use App\Models\Ms_admin_auth;
use App\Models\Tr_link_admin_user_admin_auth;

class UserController extends AdminController
{
    //const COMMON_DATE_FORMAT = 'Y-m-d H:i:s';
    const AUTH_TYPE_MASTER = 'master_admin.all';

    /* ユーザ一覧画面の表示 */
    public function showUserList(){
        $userList = Tr_admin_user::all();

        return view('admin.user_list', compact('userList', $userList));
    }

    /* ユーザ新規登録画面の表示 */
    public function showUserInput(){
        $authList = Ms_admin_auth::where('master_type', 1)->get()->toArray();
        return view('admin.user_input', compact('authList', $authList));
    }

    /* ユーザ新規登録処理 */
    public function insertAdminUser(AdminUserRequest $request){

        // 管理者名
        $admin_name = $request->input('inputAdminName');
        // ログインID
        $login_id = $request->input('inputLoginId');
        // パスワード
        $password = $request->input('inputPassword');
        if(!empty($password)){
            $password = md5($password);
        }
        // 現在時刻
        $timestamp = time() ;

	    // 管理者テーブルにインサート
        $user = Tr_admin_user::create([
            'admin_name' => $admin_name,
            'login_id' => $login_id,
            'password' => $password,
            'registration_date' => date('Y-m-d H:i:s', $timestamp),
            'last_update_date' => date('Y-m-d H:i:s', $timestamp),
            'last_login_date' => date('Y-m-d H:i:s', $timestamp),
            'delete_flag' => 0,
        ]);

        // 権限
        $authList = $request->input('auths');

        foreach ($authList as $auth) {
            // 管理者権限中間テーブルにインサート
            Tr_link_admin_user_admin_auth::create([
                'admin_id' => $user->id,
                'auth_id' => $auth,
            ]);
        }

        return redirect('/admin/user/list');
    }

    /* ユーザ情報編集画面の表示 */
    public function showUserModify(Request $request){

        // 管理者ID
        $admin_id = $request->input('id');
        if (!parent::isExistAdminUserById($admin_id, false)) {
            return redirect('/admin/top');
        }

        // 一般ユーザが自身のid以外で入ってきた場合
        if (session('user_session_key_master_flg') === '0'
            && session('user_session_key_admin_id') != $admin_id) {
            return redirect('/admin/top');
        }

        // 許可された権限情報
        $user = Tr_admin_user::find($admin_id);
        $authList = array();
        $master_flg = '0';
        foreach ($user->auths as $auth) {
            if ($auth->auth_name.'.'.$auth->auth_type === self::AUTH_TYPE_MASTER) {
                $master_flg = '1';
            }
            $authList[$auth->id] = $auth->auth_name . $auth->auth_type;
        }

        return view('admin.user_modify', compact(
            'user', $user,
            'authList', $authList,
            'master_flg', $master_flg));
    }

    /* ユーザ情報更新処理 */
    public function updateAdminUser(AdminUserRequest $request){

        // 管理者ID
        $admin_id = $request->input('id');
        // 管理者名
        $admin_name = $request->input('inputAdminName');
        // ログインID
        $login_id = $request->input('inputLoginId');
        // パスワード
        $password = $request->input('inputPassword');
        if(!empty($password)){
            $password = md5($password);
        }
        // 現在時刻
        $timestamp = time();

        // 管理者テーブルをアップデート
        Tr_admin_user::where('id', $admin_id)->update([
            'admin_name' => $admin_name,
            'login_id' => $login_id,
            'password' => $password,
            'last_update_date' => date('Y-m-d H:i:s', $timestamp),
        ]);

        // 権限
        $postAuths = $request->input('postAuths');
        $authList = explode(',', $postAuths);

        // マスター管理者かどうか
        if ($request->input('master_flg') === '0') {
            // 管理者権限中間テーブルをデリートインサート
            $deletedRows = Tr_link_admin_user_admin_auth::where('admin_id', $admin_id)->delete();
            foreach ($authList as $auth) {
                Tr_link_admin_user_admin_auth::create([
                    'admin_id' => $admin_id,
                    'auth_id' => $auth,
                ]);
            }
            return redirect('/admin/top');
        }
        return redirect('/admin/user/list');
    }

    /* ユーザ論理削除処理 */
    public function deleteAdminUser(Request $request){

        // 管理者ID
        $admin_id = $request->input('id');

        // 既に論理削除済みユーザの場合
        if (!parent::isExistAdminUserById($admin_id, false)) {
            return redirect('/admin/top');
        }

        // マスター管理者は削除不可
        if (parent::isExistAuth($admin_id, self::AUTH_TYPE_MASTER)) {
            return redirect('/admin/top');
        }

        // 現在時刻
        $timestamp = time();

        dd($timestamp);

        // 管理者テーブルをアップデート
        Tr_admin_user::where('id', $admin_id)->update([
            'last_update_date' => date('Y-m-d H:i:s', $timestamp),
            'delete_flag' => 1,
            'delete_date' => date('Y-m-d H:i:s', $timestamp),
        ]);

        return redirect('/admin/user/list');
    }
}
