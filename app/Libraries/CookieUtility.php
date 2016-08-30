<?php
/**
 * Cookieユーティリティ
 *
 */
namespace App\Libraries;
use Cookie;
class CookieUtility
{
    // nameのprefix
    const COOKIE_NAME_PREFIX = 'eroute_';

    // name属性 ユーザID
    const COOKIE_NAME_USER_ID = 'user_id';

    // expires属性 1ヶ月
    const COOKIE_TIME_MONTH = 44640;
    
    /**
     * set new cookie
     */
    public static function set($name, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true){
        // 有効期限を指定しない場合、デフォルトで1ヶ月とする
        $minutes = ($minutes == 0) ? self::COOKIE_TIME_MONTH : $minutes;
        Cookie::queue(self::COOKIE_NAME_PREFIX.$name, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }

    /**
     * get cookie
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public static function get($name){
        return Cookie::get(self::COOKIE_NAME_PREFIX .$name);
    }

    /**
     * delete cookie
     * @param string $name
     */
    public static function delete($name){
        Cookie::queue(self::COOKIE_NAME_PREFIX.$name, null, -2628000);
    }
}
