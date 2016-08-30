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
        // cookieに有効なユーザIDが保存されているか
        if (!Tr_users::find(CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))) {
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
