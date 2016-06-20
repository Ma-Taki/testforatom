<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Tr_admin_user;
use App\Libraries\UserUtility;
use App\Libraries\SessionUtility;

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
        $requestUri = $request->path();
        $admin_id = session(SessionUtility::SESSION_KEY_ADMIN_ID);
        $authList = Tr_admin_user::find($admin_id)->auths;

        $permission = false;
        foreach ($authList as $auth) {
            // 権限あり、またはマスター管理者の場合、遷移を行う
            if ($auth->auth_name.'.'.$auth->auth_type === UserUtility::AUTH_LIST[$requestUri]
                || $auth->auth_name.'.'.$auth->auth_type === UserUtility::AUTH_TYPE_MASTER){
                $permission = true;
                break;
            }
        }
        return $permission ? $next($request) : redirect('admin/error');
    }
}
