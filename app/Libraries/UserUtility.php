<?php
/**
 * ユーザーライブラリ
 *
 */
namespace App\Libraries;

use App\Libraries\SessionUtility;
use App\Models\Tr_admin_user;

class UserUtility
{
    // 権限：マスター管理者
    const AUTH_TYPE_MASTER = 'master_admin.all';
    // 権限：案件管理：検索・照会
    const AUTH_TYPE_ITEM_READ = 'item.read';
    // 権限：案件管理：新規登録
    const AUTH_TYPE_ITEM_CREATE = 'item.create';
    // 権限：案件管理：更新
    const AUTH_TYPE_ITEM_UPDATE = 'item.update';
    // 権限：案件管理：削除
    const AUTH_TYPE_ITEM_DELETE = 'item.delete';
    // 権限：会員管理：検索：照会
    const AUTH_TYPE_MEMBER_READ = 'member.read';
    // 権限：会員管理：削除
    const AUTH_TYPE_MEMBER_DELETE = 'member.delete';
    // 権限：エントリー管理：検索・照会
    const AUTH_TYPE_ENTRY_READ = 'entry.read';
    // 権限：エントリー管理：削除
    const AUTH_TYPE_ENTRY_DELETE = 'entry.delete';
    // 権限：エントリー管理：スキルシートDL
    const AUTH_TYPE_ENTRY_DOWNLOAD = 'entry.download';

    // パスに対応した必要権限リスト
    const AUTH_LIST = [
        // ユーザ管理
        'admin/user/list' => self::AUTH_TYPE_MASTER,
        'admin/user/input' => self::AUTH_TYPE_MASTER,
        'admin/user/insert' => self::AUTH_TYPE_MASTER,
        'admin/user/modify' => self::AUTH_TYPE_MASTER,
        'admin/user/update' => self::AUTH_TYPE_MASTER,
        'admin/user/delete' => self::AUTH_TYPE_MASTER,
        //エントリー管理
        'admin/entry/list' => self::AUTH_TYPE_ENTRY_READ,
        'admin/entry/detail' => self::AUTH_TYPE_ENTRY_READ,
        'admin/entry/search' => self::AUTH_TYPE_ENTRY_READ,
        'admin/entry/delete' => self::AUTH_TYPE_ENTRY_DELETE,
        'admin/entry/download' => self::AUTH_TYPE_ENTRY_DOWNLOAD,
        // 会員管理
        'admin/member/list' => self::AUTH_TYPE_MEMBER_READ,
        'admin/member/detail' => self::AUTH_TYPE_MEMBER_READ,
        'admin/member/search' => self::AUTH_TYPE_MEMBER_READ,
        'admin/member/update' => self::AUTH_TYPE_MEMBER_READ,
        'admin/member/delete' => self::AUTH_TYPE_MEMBER_DELETE,
        // 案件管理
        'admin/item/search' => self::AUTH_TYPE_ITEM_READ,
    ];

    // サイドバー表示制御用：案件
    //const SIDE_BAR_DISPLAY_ITEM = 'item';
    // サイドバー表示制御用：会員
    //const SIDE_BAR_DISPLAY_ITEM = 'member';
    // サイドバー表示制御用：エントリー
    //const SIDE_BAR_DISPLAY_ITEM = 'entry';
    // サイドバー表示制御用：ユーザ
    //const SIDE_BAR_DISPLAY_ITEM = 'user';

    /**
     * 管理者idで指定されたユーザが指定された権限を持っているかをチェックする
     *
     * @param $id 管理者id
     * @param $authName 権限名
     * @return bool
     **/
    public static function isExistAuth($id, $authName){
        $user = Tr_admin_user::find($id);
        if ($user != null) {
            foreach ($user->auths as $auth) {
                if ($auth->auth_name.'.'.$auth->auth_type === $authName) return true;
            }
        }
        return false;
    }

    /**
     * サイドバーにサブメニューを表示するかを返す
     * 検索・照会権限を持っている場合にtrueになる
     * @param  string  $subMenu
     * @return bool
     */
    public static function isDisplaySubMenu($subMenu){
        return self::isExistAuth(session(SessionUtility::SESSION_KEY_ADMIN_ID), $subMenu);
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
