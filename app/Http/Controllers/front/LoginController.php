<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests\front\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Tr_users;
use App\Libraries\FrontUtility as FrontUtil;
use App\Libraries\CookieUtility as CkieUtil;
use Log;
use Cookie;
use Carbon\Carbon;
use DB;

class LoginController extends Controller
{
    /**
     * ログイン画面表示
     * GET:/login
     */
    public function index(){
        return view('front.login');
    }

    /**
     * ログイン処理
     * POST:/login
     */
    public function store(LoginRequest $request){

        // ユーザを取得する
        $user = Tr_users::where('mail', $request->email)
                        ->enable()
                        ->get();

        // 一応、２件以上取得したらcritical
        if ($user->count() >= 2) {
            Log::critical('Duplicate email-address:' .$user->first()->mail);
            abort(503, '');
        }

        // DBに対象ユーザが存在しない、または削除済み
        if($user->isEmpty()){
            // 認証失敗
            return back()->with('custom_error_messages',['メールアドレス又はパスワードに誤りがあります。'])->withInput();

        }else{
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
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });

            // cookieを付加する
            CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);
        }
        // トップ画面に遷移
        return redirect('/');
    }

    public function logout(){
        // cookieを削除
        CkieUtil::delete(CkieUtil::COOKIE_NAME_USER_ID);
        // トップ画面に遷移
        return redirect('/');
    }
}
