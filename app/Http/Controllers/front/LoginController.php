<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Requests\front\LoginRequest;
use App\Http\Controllers\FrontController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Models\Tr_users;
use App\Libraries\{FrontUtility as FrontUtil, CookieUtility as CkieUtil};
use Log;
use Cookie;
use Carbon\Carbon;
use DB;

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
            abort(503);
        }

        // パスワード照合
        $user = $user->first();
        $password = md5($user->salt .$request->password .FrontUtil::FIXED_SALT);
        if ($user->password != $password) {
            // 認証失敗
            Log::debug('input:'.$password.' db:'.$user->password);
            return back()->with('custom_error_messages',['メールアドレス又はパスワードに誤りがあります。'])->withInput();
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

        // cookieを付与する
        CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

        return redirect($request->next);
    }

    /**
     * ログアウト処理
     * GET:/logout
     */
    public function logout(){
        // cookieを削除
        CkieUtil::delete(CkieUtil::COOKIE_NAME_USER_ID);
        // トップ画面に遷移
        return redirect('/');
    }
}
