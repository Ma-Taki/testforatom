<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Illuminate\Http\Request;

use App\Models\Tr_admin_user;

class AdminController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * ログアウト処理
     * /admin/logout
     */
    protected function logout(Request $request){
        // session全データ削除
        $request->session()->flush();
        return redirect('/admin/login');
    }

    /**
     * idで指定されたユーザがadmin_userテーブルに存在するかをチェックする
     *
     * @param $id id
     * @param $includeDeleted 論理削除済みデータを含むか
     * @return bool
     **/
    protected function isExistAdminUserById($id, $includeDeleted){
        $user = Tr_admin_user::find($id);
        if ($includeDeleted) {
            return $user != null;
        } else {
            return $user != null && !$user->delete_flag;
        }
        return false;
    }

    /**
     * idで指定されたユーザが指定された権限を持っているかをチェックする
     *
     * @param $id id
     * @param $authName 権限名
     * @return bool
     **/
    public function isExistAuth($id, $authName){
        $user = Tr_admin_user::find($id);
        if ($user != null) {
            foreach ($user->auths as $auth) {
                if ($auth->auth_name.'.'.$auth->auth_type === $authName) return true;
            }
        }
        return false;
    }
}
