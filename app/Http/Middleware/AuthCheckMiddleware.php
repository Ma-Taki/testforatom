<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Tr_admin_user;
use App\Libraries\UserUtility;

class AuthCheckMiddleware
{
    /**
     * 遷移先のパスに対しての権限チェック
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userUtility = new UserUtility();
        $requestUri = $request->path();
        $admin_id = session('user_session_key_admin_id');
        $authList = Tr_admin_user::find($admin_id)->auths;

        $authPermission = false;
        foreach ($authList as $auth) {
            if ($auth->auth_name.'.'.$auth->auth_type === $userUtility->getAuthByPath($requestUri)
                || $auth->auth_name.'.'.$auth->auth_type === $userUtility::AUTH_TYPE_MASTER){
                $authPermission = true;
                break;
            }
        }
        return $authPermission ? $next($request) : redirect('admin/error');
    }
}
