<?php
/**
 * セッションユーティリティ
 *
 */
namespace App\Libraries;

class SessionUtility
{
    // セッションキー：管理者ID
    const SESSION_KEY_ADMIN_ID = 'user_session_key_admin_id';
    // セッションキー：管理者名
    const SESSION_KEY_ADMIN_NAME = 'user_session_key_admin_name';
    // セッションキー：ログインID
    const SESSION_KEY_LOGIN_ID = 'user_session_key_login_id';
    // セッションキー：マスターフラグ (true or false)
    const SESSION_KEY_MASTER_FLG = 'user_session_key_master_flg';

}
