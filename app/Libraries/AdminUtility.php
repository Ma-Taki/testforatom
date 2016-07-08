<?php
/**
 * 管理画面汎用ユーティリティー
 *
 */
namespace App\Libraries;

use App\Libraries\ModelUtility as mdlUtil;
use App\Libraries\SessionUtility as ssnUtil;
use App\Models\Tr_admin_user;

class AdminUtility
{
    // パスに対応した必要権限リスト
    const AUTH_LIST = [
        // ユーザ管理
        'admin/user/list' => mdlUtil::AUTH_TYPE_MASTER,
        'admin/user/input' => mdlUtil::AUTH_TYPE_MASTER,
        'admin/user/insert' => mdlUtil::AUTH_TYPE_MASTER,
        'admin/user/modify' => mdlUtil::AUTH_TYPE_MASTER,
        'admin/user/update' => mdlUtil::AUTH_TYPE_MASTER,
        'admin/user/delete' => mdlUtil::AUTH_TYPE_MASTER,
        //エントリー管理
        'admin/entry/detail' => mdlUtil::AUTH_TYPE_ENTRY_READ,
        'admin/entry/search' => mdlUtil::AUTH_TYPE_ENTRY_READ,
        'admin/entry/delete' => mdlUtil::AUTH_TYPE_ENTRY_DELETE,
        'admin/entry/download' => mdlUtil::AUTH_TYPE_ENTRY_DOWNLOAD,
        // 会員管理
        'admin/member/detail' => mdlUtil::AUTH_TYPE_MEMBER_READ,
        'admin/member/search' => mdlUtil::AUTH_TYPE_MEMBER_READ,
        'admin/member/update' => mdlUtil::AUTH_TYPE_MEMBER_READ,
        'admin/member/delete' => mdlUtil::AUTH_TYPE_MEMBER_DELETE,
        // 案件管理
        'admin/item/search' => mdlUtil::AUTH_TYPE_ITEM_READ,
        'admin/item/detail' => mdlUtil::AUTH_TYPE_ITEM_READ,
        'admin/item/input' => mdlUtil::AUTH_TYPE_ITEM_CREATE,
        'admin/item/insert' => mdlUtil::AUTH_TYPE_ITEM_CREATE,
        'admin/item/modify' => mdlUtil::AUTH_TYPE_ITEM_UPDATE,
        'admin/item/update' => mdlUtil::AUTH_TYPE_ITEM_UPDATE,
        'admin/item/delete' => mdlUtil::AUTH_TYPE_ITEM_DELETE,
    ];

    /**
     * ログインユーザが指定された権限を持っているかをチェックする
     *
     * @param $authName 権限名
     * @return bool
     **/
    public static function isExistAuth($authName){
        $admin_id = session(ssnUtil::SESSION_KEY_ADMIN_ID);
        $user = Tr_admin_user::find($admin_id);
        if ($user != null) {
            foreach ($user->auths as $auth) {
                if ($auth->auth_name.'.'.$auth->auth_type === $authName) return true;
            }
        }
        return false;
    }

    /**
     * ユーザ情報更新画面にて、編集者と編集対象の関係から
     * 権限の必須チェックを行うか判定する
     * @param
     * @param
     * @return
     */
     public static function isValidationAuths($eMasFlg, $sMasFlg){
         if (!$eMasFlg && $sMasFlg) {
             return 1;
         }
         return 0;
     }



}
