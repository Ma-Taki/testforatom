<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\SessionUtility;

class LoginCheckMiddleware
{
    /**
     * ログインチェック
     * 未ログインの場合ログイン画面へリダイレクトさせる
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // sessoinにログインIDが保存されているか
        if (!$request->session()->has(SessionUtility::SESSION_KEY_LOGIN_ID)) {
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
