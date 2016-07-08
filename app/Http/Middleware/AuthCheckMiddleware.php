<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Tr_admin_user;
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\SessionUtility as ssnUtil;
use App\Libraries\ModelUtility as mdlUtil;

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
        $admin_id = session(ssnUtil::SESSION_KEY_ADMIN_ID);
        $authList = Tr_admin_user::find($admin_id)->auths;

        $permission = false;
        foreach ($authList as $auth) {
            // 権限あり、またはマスター管理者の場合、遷移を行う
            if ($auth->auth_name.'.'.$auth->auth_type === admnUtil::AUTH_LIST[$requestUri]
                || $auth->auth_name.'.'.$auth->auth_type === mdlUtil::AUTH_TYPE_MASTER){
                $permission = true;
                break;
            }
        }

        // ログイン中のIDに紐づくユーザ情報照会、一部更新については、常に許可する
        if (($requestUri === 'admin/user/modify' && $admin_id == $request->input('id'))
            || ($requestUri === 'admin/user/update' && $admin_id == $request->input('admin_id'))) {
                $permission = true;
        }


        return $permission ? $next($request) : abort(403,' 権限がありません。');
    }
}
