<?php
/**
 * Model関連ユーティリティー
 *
 */
namespace App\Libraries;

class ModelUtility
{
    /* ▽▽▽ 管理権限 ▽▽▽ */
    // マスター管理者
    const AUTH_TYPE_MASTER = 'master_admin.all';
    // 案件管理：検索・照会
    const AUTH_TYPE_ITEM_READ = 'item.read';
    // 案件管理：新規登録
    const AUTH_TYPE_ITEM_CREATE = 'item.create';
    // 案件管理：更新
    const AUTH_TYPE_ITEM_UPDATE = 'item.update';
    // 案件管理：削除
    const AUTH_TYPE_ITEM_DELETE = 'item.delete';
    // 会員管理：検索：照会
    const AUTH_TYPE_MEMBER_READ = 'member.read';
    // 会員管理：削除
    const AUTH_TYPE_MEMBER_DELETE = 'member.delete';
    // エントリー管理：検索・照会
    const AUTH_TYPE_ENTRY_READ = 'entry.read';
    // エントリー管理：削除
    const AUTH_TYPE_ENTRY_DELETE = 'entry.delete';
    // エントリー管理：スキルシートDL
    const AUTH_TYPE_ENTRY_DOWNLOAD = 'entry.download';
    // メルマガ管理：
    const AUTH_TYPE_MAIL_MAGAZINE = 'mail_magazine.all';
    /* △△△ 管理権限 △△△ */

    // 都道府県：東京
    const PREFECTURES_ID_TOKYO = 13;

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

    // 認証キー発行時の目的：パスワード再設定
    const AUTH_TASK_RECOVER_PASSWORD = 'RecoverPassword';
    // 認証キー発行時の目的：メールアドレス認証（新規会員登録）
    const AUTH_TASK_REGIST_MAIL_AUHT = 'RegistMailAuth';
    // 認証キー発行時の目的：メールアドレス認証（メールアドレス変更）
    const AUTH_TASK_CHANGE_MAIL_AUHT = 'ChangeMailAuth';

    // ソーシャルタイプ：Twitter
    const SOCIAL_TYPE_TWITTER = 1;
    // ソーシャルタイプ：Facebook
    const SOCIAL_TYPE_FACEBOOK = 2;
    // ソーシャルタイプ：Github
    const SOCIAL_TYPE_GITHUB = 3;

    // ユーザの印象レベル：優良
    const USER_IMPRESSION_EXCELLENT = 4;
    // ユーザの印象レベル：普通
    const USER_IMPRESSION_NORMAL = 3;
    // ユーザの印象レベル：いまいち
    const USER_IMPRESSION_NOTGOOD = 2;
    // ユーザの印象レベル：ブラック
    const USER_IMPRESSION_BLACK = 1;

    //ユーザー進捗状況
    const UNSUPPORTED = 0;//未対応
    const IN_ADJUSTMENT_COUNCELING = 1;//カウンセリング調整中
    const FINISHED_COUNCELING = 2;//カウンセリング済
    const IN_ADJUSTMENT_INTERVIEW = 3;//クライアント面談調整中
    const FINISHED_INTERVIEW = 4;//クライアント面談済
    const IN_OPERATION = 5; //稼働中
    const EXIT_OPERATION = 6; //終了
    const STOP_OPERATION = 7; //営業中止
    const IN_OPARATION_AT_OTHER_COMPANY = 8;//他社稼働中

    /**
     * 引数のモデル配列のname属性を'、'で連結して返す。
     * @param array $array
     * @return string
     */
    public static function getNameAll($array){
        if ($array == null || $array->isEmpty()) return '';
        $str = '';
        foreach ($array as $value) {
            $str .= $value->name .'、';
        }
        return rtrim($str, '、');
    }
}
