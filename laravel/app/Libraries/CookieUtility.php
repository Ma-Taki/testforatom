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
     * @param int $flag 0(検討中案件) 1(ログイン)
     * @param string $response 検討中案件:null ログイン:遷移先URL
     */
    public static function set($name, $value, $minutes = 0, $flag = 0, $response = null, $path = null, $domain = null, $secure = false, $httpOnly = true){
        // 有効期限を指定しない場合、デフォルトで1ヶ月とする
        $minutes = ($minutes == 0) ? self::COOKIE_TIME_MONTH : $minutes;
        
        if($flag == 0){
            //検討中案件のとき
            Cookie::queue(self::COOKIE_NAME_PREFIX.$name, $value, $minutes, $path, $domain, $secure, $httpOnly);
        }elseif($flag == 1){
            //ログインのとき
            return redirect($response)->cookie(self::COOKIE_NAME_PREFIX.$name, $value, $minutes, $path, $domain, $secure, $httpOnly);
        }
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
