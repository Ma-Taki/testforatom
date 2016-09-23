<?php

namespace App\Http\Middleware\front;

use Closure;
use App\Libraries\CookieUtility as CkieUtil;
use App\Models\Tr_users;

class LoginCheckMiddleware
{
    /**
     * ログインチェック
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // リクエストURL
        $requestUrl = $request->path();
        // ログインユーザを取得
        $user = Tr_users::getLoginUser()->first();
        if (empty($user)) {
            if ($requestUrl == 'entry') {
                $next = '/' .$requestUrl .'?' .$request->getQueryString();
                // ログイン画面
                return redirect('/login?next=' .$next);
            }
            // トップ
            return redirect('/');
        }
        return $next($request);
    }
}
