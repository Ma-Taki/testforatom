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
        'admin/item/detail' => self::AUTH_TYPE_ITEM_READ,
        'admin/item/input' => self::AUTH_TYPE_ITEM_CREATE,
        'admin/item/insert' => self::AUTH_TYPE_ITEM_CREATE,
        'admin/item/modify' => self::AUTH_TYPE_ITEM_UPDATE,
        'admin/item/update' => self::AUTH_TYPE_ITEM_UPDATE,
        'admin/item/delete' => self::AUTH_TYPE_ITEM_DELETE,
    ];



}
