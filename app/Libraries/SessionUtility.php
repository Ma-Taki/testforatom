<?php
/**
 * セッションユーティリティ
 *
 */
namespace App\Libraries;

class SessionUtility
{
    /* ▽▽▽ 管理画面用 ▽▽▽ */
    // 管理者ID
    const SESSION_KEY_ADMIN_ID = 'user_session_key_admin_id';
    // 管理者名
    const SESSION_KEY_ADMIN_NAME = 'user_session_key_admin_name';
    // ログインID
    const SESSION_KEY_LOGIN_ID = 'user_session_key_login_id';
    // マスターフラグ (true or false)
    const SESSION_KEY_MASTER_FLG = 'user_session_key_master_flg';
    /* △△△ 管理画面用 △△△ */

    /* ▽▽▽ 公開画面用 ▽▽▽ */
    // SNS連携　ソーシャルタイプ
    const SESSION_KEY_SOCIAL_TYPE = 'social_conection_type';
    // SNS連携　ソーシャルID
    const SESSION_KEY_SOCIAL_ID = 'social_conection_id';
    /* △△△ 公開画面用 △△△ */
}
