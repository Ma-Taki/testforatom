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
   * 初期表示画面
   * GET:admin/Login
   */
  public function index(){
      return view('admin.login');
  }
  /**
   * ログイン処理
   * POST:admin/Login
   */
  public function store(LoginRequest $request){

      $login_id = $request->input('login_id');
      $password = $request->input('password');

      // ハッシュパスワード確認用
      //dd(md5(''));

      $user = Tr_admin_user::where('login_id', $login_id)->where('password', md5($password))->where('delete_flag', 0)->get();

      if($user->isEmpty()){
          //認証失敗
          $data = array('auth_error'=>1);
          return view('admin.login', $data);

      }else{
          //認証成功
          session([SessionUtility::SESSION_KEY_ADMIN_ID => $user->first()->id]);
          session([SessionUtility::SESSION_KEY_ADMIN_NAME => $user->first()->admin_name]);
          session([SessionUtility::SESSION_KEY_LOGIN_ID => $login_id]);

          // ログイン時にmaster_adminかどうかをsessionに保存
          $adUser = Tr_link_admin_user_admin_auth::where(
            'admin_id', $user->first()->id)->where('auth_id', 1)->get();
          if($adUser->isEmpty()){
              session([SessionUtility::SESSION_KEY_MASTER_FLG => 0]);
          } else {
              session([SessionUtility::SESSION_KEY_MASTER_FLG => 1]);
          }

          // 現在時刻
          $timestamp = time();
          // 管理者テーブルをアップデート
          Tr_admin_user::where('id', $user->first()->id)->update([
              'last_login_date' => date('Y-m-d H:i:s', $timestamp),
              ]);
          return redirect('/admin/top');
      }
  }
}