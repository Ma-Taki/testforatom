<?php
/**
 * ユーザーライブラリ
 *
 */
namespace App\Libraries;

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

    public function __construct(){
    }




    /* パスに対応して必要な権限を返す  */
    public function getAuthByPath($requestPath){
        switch ($requestPath) {
            case 'admin/user/list':      return self::AUTH_TYPE_MASTER; break;
            case 'admin/user/input':     return self::AUTH_TYPE_MASTER; break;
            case 'admin/user/insert':    return self::AUTH_TYPE_MASTER; break;
            case 'admin/user/delete':    return self::AUTH_TYPE_MASTER; break;
            case 'admin/entry/list':     return self::AUTH_TYPE_ENTRY_READ; break;
            case 'admin/entry/detail':   return self::AUTH_TYPE_ENTRY_READ; break;
            case 'admin/entry/delete':   return self::AUTH_TYPE_ENTRY_DELETE; break;
            case 'admin/entry/download': return self::AUTH_TYPE_ENTRY_DOWNLOAD; break;
            default:
                break;
        }
        return "";
    }
}
