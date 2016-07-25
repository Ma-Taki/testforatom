<?php
/**
 * Model関連ユーティリティー
 *
 */
namespace App\Libraries;

class ModelUtility
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

    // タグタイプ：通常
    const TAG_TYPE_NORMAL = 1;
    // タグタイプ：ピックアップ
    const TAG_TYPE_PICK_UP = 2;
    // タグタイプ：特集
    const TAG_TYPE_FEATURE = 3;

    // マスタータイプ：検索、保有、表示
    const MASTER_TYPE_ACTUAL_DATA = 1;
    // マスタータイプ：表示、保有
    const MASTER_TYPE_DISPLAY_ONLY = 2;
    // マスタータイプ：検索
    const MASTER_TYPE_INDEX_ONLY = 3;
}