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
use App\Libraries\SessionUtility;
use App\Libraries\UserUtility;

class UserController extends AdminController
{
    /* ユーザ一覧画面の表示 */
    public function showUserList(){
        $userList = Tr_admin_user::all();

        return view('admin.user_list', compact('userList', $userList));
    }

    /* ユーザ新規登録画面の表示 */
    public function showUserInput(){
        return view('admin.user_input');
    }

    /* ユーザ新規登録処理 */
    public function insertAdminUser(AdminUserRegistRequest $request){

        // 管理者名
        $admin_name = $request->input('admin_name');
        // ログインID
        $login_id = $request->input('login_id');
        // パスワード
        $password = $request->input('password');
        if(!empty($password)){
            $password = md5($password);
        }
        // 現在時刻
        $timestamp = time() ;

        // 権限
        $authList = $request->input('auths');
        dd($authList);

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

        // 許可された権限情報
        $user = Tr_admin_user::find($admin_id);
        $authList = array();
        $master_flg = '0';
        foreach ($user->auths as $auth) {
            if ($auth->auth_name.'.'.$auth->auth_type === UserUtility::AUTH_TYPE_MASTER) {
                $master_flg = '1';
            }
            $authList[] = $auth->id;
        }

        return view('admin.user_modify', compact(
            'user', $user,
            'authList', $authList,
            'master_flg', $master_flg));
    }

    /* ユーザ情報更新処理 */
    public function updateAdminUser(AdminUserEditRequest $request){

        // 管理者ID
        $admin_id = $request->input('admin_id');
        // 管理者名
        $admin_name = $request->input('admin_name');
        // ログインID
        $login_id = $request->input('login_id');
        // パスワード
        $password = $request->input('password');
        if(!empty($password)){
            $password = md5($password);
        }

        // 現在時刻
        $timestamp = time();

        // 管理者テーブルをアップデート
        if (!empty($password)) {
            Tr_admin_user::where('id', $admin_id)->update([
                'admin_name' => $admin_name,
                'login_id' => $login_id,
                'password' => $password,
                'last_update_date' => date('Y-m-d H:i:s', $timestamp),
            ]);
        } else {
            // passwordは変更がない場合渡ってこないので処理を分ける
            Tr_admin_user::where('id', $admin_id)->update([
                'admin_name' => $admin_name,
                'login_id' => $login_id,
                'last_update_date' => date('Y-m-d H:i:s', $timestamp),
            ]);
        }

        // 権限
        $postAuths = $request->input('postAuths');
        $authList = explode(',', $postAuths);

        // 編集者：マスター管理者　かつ　編集対象：一般管理者の場合のみ権限の更新を行う
        if ($request->input('master_flg') === '0'
            && session(SessionUtility::SESSION_KEY_MASTER_FLG)) {
            // 管理者権限中間テーブルをデリートインサート
            $deletedRows = Tr_link_admin_user_admin_auth::where('admin_id', $admin_id)->delete();
            foreach ($authList as $auth) {
                Tr_link_admin_user_admin_auth::create([
                    'admin_id' => $admin_id,
                    'auth_id' => $auth,
                ]);
            }
        }

        // ログイン中の管理者名を更新した場合、sessionに上書き保存
        if ($admin_id == session(SessionUtility::SESSION_KEY_ADMIN_ID)) {
            session([SessionUtility::SESSION_KEY_ADMIN_NAME => $admin_name]);
        }

        $redirectPath = '/admin/user/list';
        if (!session(SessionUtility::SESSION_KEY_MASTER_FLG)) {
            $redirectPath = '/admin/top';
        }
        return redirect($redirectPath);
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
        if (parent::isExistAuth($admin_id, UserUtility::AUTH_TYPE_MASTER)) {
            return redirect('/admin/top');
        }

        // 現在時刻
        $timestamp = time();

        // 管理者テーブルをアップデート
        Tr_admin_user::where('id', $admin_id)->update([
            'last_update_date' => date('Y-m-d H:i:s', $timestamp),
            'delete_flag' => 1,
            'delete_date' => date('Y-m-d H:i:s', $timestamp),
        ]);

        return redirect('/admin/user/list');
    }
}
