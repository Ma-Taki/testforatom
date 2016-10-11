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
        'admin/user/modify' => mdlUtil::AUTH_TYPE_MASTER,
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
        'admin/item/modify' => mdlUtil::AUTH_TYPE_ITEM_UPDATE,
        'admin/item/delete' => mdlUtil::AUTH_TYPE_ITEM_DELETE,
    ];

    /**
     * ログインユーザが指定された権限を持っているかをチェックする
     *
     * @param $authName 権限名
     * @return bool
     **/
    public static function isExistAuth($authName){
        return self::isExistAuthById(session(ssnUtil::SESSION_KEY_ADMIN_ID), $authName);
    }

    /**
     * idで指定されたユーザが指定された権限を持っているかをチェックする
     *
     * @param $id 管理者id
     * @param $authName 権限名
     * @return bool
     **/
    public static function isExistAuthById($id, $authName){
        $user = Tr_admin_user::find($id);
        if ($user != null) {
            foreach ($user->auths as $auth) {
                if ($auth->auth_name.'.'.$auth->auth_type === $authName) return true;
            }
        }
        return false;
    }

    /**
     * 受け取った配列のバリューを"、"で連結した文字列に変換する。
     *
     * @param Collection $models
     * @return string
     **/
    public static function convertModelsToSNSString($models){
        $str = '';
        $function = function($account_type) {
            switch ($account_type) {
                case mdlUtil::SOCIAL_TYPE_TWITTER:
                    return 'Twitter、';
                case mdlUtil::SOCIAL_TYPE_FACEBOOK:
                    return 'Facebook、';
                case mdlUtil::SOCIAL_TYPE_GITHUB:
                    return 'GitHub、';
                default:
                    return '';
            }
        };
        if (!empty($models)) {
            foreach ($models as $value) {
                $str .= $function($value->social_account_type);
            }
        }
        return rtrim($str, '、');
    }

    /**
     * 引数の文字列をスペースで分割した配列に変換する。
     * また、空要素は削除し、indexを採番し直す。
     *
     * @param string str
     * @return array
     */
    public static function convertArrayToSearchStr($str) {
        return  array_values(array_filter(explode(' ', mb_convert_kana($str, 's')), 'strlen'));
    }
}
