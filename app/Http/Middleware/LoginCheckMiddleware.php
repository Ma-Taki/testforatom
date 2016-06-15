<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin_user;

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
        if (!$request->session()->has('user_session_key_login_id')) {
            return redirect('/admin/login');
        }

        /* DBに存在するか確認
        $session_key = $request->session()->get('user_session_key_login_id', '');
        $user = Admin_user::where('login_id', $session_key)->get();
        if($user->isEmpty()) {
            return view('admin.login');
        }
        */

        return $next($request);
    }
}
