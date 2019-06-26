<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Requests\front\LoginRequest;
use App\Http\Controllers\FrontController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Models\Tr_users;
use App\Models\Tr_considers;
use App\Libraries\{FrontUtility as FrontUtil, CookieUtility as CkieUtil, SessionUtility as SsnUtil};
use Log;
use Cookie;
use Carbon\Carbon;
use DB;
use App\Models\Tr_auth_keys;
use App\Libraries\ModelUtility as MdlUtil;

class LoginController extends FrontController
{
    /**
     * ログイン画面表示
     * GET:/login
     */
    public function index(Request $request){
        // ログイン後のリダイレクト先を決めておく
        $next = $request->next ? $request->next : '/';
        return view('front.login', ['next' => $next,]);
    }

    /**
     * ログイン処理
     * POST:/login
     */
    public function store(LoginRequest $request){
      try {
        // メールアドレスから有効なユーザを取得する
        $user = parent::getUserByMail($request->email);

      } catch (ModelNotFoundException $e) {
        return back()->with('custom_error_messages',['メールアドレス又はパスワードに誤りがあります。'])->withInput();
      }

      // 一応、２件以上取得したらcritical
      if ($user->count() >= 2) {
        Log::critical('Duplicate email-address:' .$user->first()->mail);
        abort(400);
      }

      // パスワード照合
      $user = $user->first();
      $password = md5($user->salt .$request->password .FrontUtil::FIXED_SALT);
      if ($user->password != $password) {
        // 認証失敗
        return back()->with('custom_error_messages',['メールアドレス又はパスワードに誤りがあります。'])->withInput();
      }

      // 24時間以内のアクセスかチェック
      $auth_key = Tr_auth_keys::where('mail', $user->mail)
                              ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                              ->get()
                              ->first();
                              
      if(!empty($auth_key)){
        $limit = $auth_key->application_datetime->addDay();
        $now = Carbon::now();
        // 認証確認
        if ($user->auth_flag == 1) {
          if (!$now->lte($limit)) {
            // 認証失敗
            return back()->with([
                  'custom_error_messages' => ['登録から24時間以上経過しました。登録を完了する場合はメールを送信いたしますので「再送信する」をクリックしてください。'],
                  'ticket' => $auth_key->ticket,
              ]);
          }else{
            // 認証失敗
            return back()->with('custom_error_messages',['「ご登録完了にお進みください」メールのURLをクリックして、認証を完了してください。'])->withInput();
          }
        }
      }
      // 認証成功
      $db_data = [
        'user_id' => $user->id,
        'now' => Carbon::now()->format('Y-m-d H:i:s'),
      ];

      // トランザクション
      DB::transaction(function () use ($db_data) {
        try {
          // 最終ログイン日付をアップデート
          Tr_users::where('id', $db_data['user_id'])
                    ->update(['last_login_date' => $db_data['now']]);

        } catch (Exception $e) {
          Log::error($e);
          abort(503);
        }
      });

      //cookieに検討中案件があればデータベースに追加
      if (CkieUtil::get('considers')) {
        $cookies = CkieUtil::get('considers');
        foreach ($cookies as $key => $value) {
          //すでに検討中かデータベース検索（user_idとitem_idの複合インデックス）
          $considers = Tr_considers::where('user_id',$user->id)->where('item_id',$key);
          //すでに検討中であればアップデート
          if($considers->count() > 0){
            $considers->update(['user_id' => $user->id,'item_id'=>$key,'delete_flag'=>0]);
          //まだ検討中でなければインサート
          }else{
            //データベースに登録
            $csd = new Tr_considers;
            $csd->user_id = $user->id;
            $csd->item_id = $key;
            $csd->delete_flag = 0;
            $csd->save();
          }
          //もうcookieに入っているデータは必要ないので削除
          CkieUtil::delete('considers['.$key.']');
        }
      }
      //cookieを付与してリダイレクト
      return CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id, 0, 1, $request->next);
    }

    /**
     * ログアウト処理
     * GET:/logout
     */
    public function logout(){
      // cookieを削除
      CkieUtil::delete(CkieUtil::COOKIE_NAME_USER_ID);
      //sessionを削除
      session()->forget(SsnUtil::SESSION_KEY_CONSIDERS);
      // トップ画面に遷移
      return redirect('/');
    }
}
