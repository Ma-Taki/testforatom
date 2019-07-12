<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Tr_admin_user;
use App\Libraries\AdminUtility as AdmnUtil;
use App\Libraries\SessionUtility as SsnUtil;
use App\Libraries\ModelUtility as MdlUtil;

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
        $request_uri = $request->path();
        $admin_id = session(SsnUtil::SESSION_KEY_ADMIN_ID);
        $authList = Tr_admin_user::find($admin_id)->auths;

        $permission = false;
        foreach ($authList as $auth) {
            // 権限あり、またはマスター管理者の場合、遷移を行う
            if ($auth->auth_name.'.'.$auth->auth_type === AdmnUtil::AUTH_LIST[$request_uri]
                || $auth->auth_name.'.'.$auth->auth_type === MdlUtil::AUTH_TYPE_MASTER){
                $permission = true;
                break;
            }
        }

        // ユーザ管理は、自身の照会・更新のみ常に可とする
        if ($request_uri === 'admin/user/modify' && $admin_id == $request->id) {
             $permission = true;
        }

        return $permission ? $next($request) : abort(403,' 権限がありません。');
    }
}
