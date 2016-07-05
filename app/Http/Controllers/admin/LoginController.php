<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests\admin\LoginRequest;
use App\Http\Controllers\AdminController;
use App\Models\Tr_admin_user;
use App\Models\Tr_link_admin_user_admin_auth;
use App\Libraries\SessionUtility;

class LoginController extends AdminController
{
  /**
   * ログイン処理
   * POST:/admin/Login
   */
  public function login(LoginRequest $request){

      $login_id = $request->input('login_id');
      $password = $request->input('password');

      // 入力値から、有効な管理ユーザを取得する
      $user = Tr_admin_user::where('login_id', $login_id)
                           ->where('password', md5($password))
                           ->where('delete_flag', 0)
                           ->get();

      // DBに対象ユーザが存在しない、または削除済み
      if($user->isEmpty()){
          // 認証失敗
          return back()->with('custom_error_messages', 'ログインできません。')->withInput();

      }else{
          // 認証成功
          // sessionにユーザ情報を保存
          session([SessionUtility::SESSION_KEY_ADMIN_ID => $user->first()->id]);
          session([SessionUtility::SESSION_KEY_ADMIN_NAME => $user->first()->admin_name]);
          session([SessionUtility::SESSION_KEY_LOGIN_ID => $login_id]);
          // マスター管理者フラグ
          $adUser = Tr_link_admin_user_admin_auth::where('admin_id', $user->first()->id)
                                                 ->where('auth_id', 1)
                                                 ->get();
          if($adUser->isEmpty()){
              session([SessionUtility::SESSION_KEY_MASTER_FLG => false]);
          } else {
              session([SessionUtility::SESSION_KEY_MASTER_FLG => true]);
          }

          // 管理者テーブルをアップデート
          Tr_admin_user::where('id', $user->first()->id)
                       ->update([
              'last_login_date' => date('Y-m-d H:i:s', time()),
              ]);
          return redirect('/admin/top');
      }
  }
}
