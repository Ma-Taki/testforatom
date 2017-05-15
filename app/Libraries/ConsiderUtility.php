<?php
/**
 * Considerユーティリティ
 *
 */
namespace App\Libraries;
use App\Libraries\CookieUtility as CkieUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\SessionUtility as SsnUtil;
use App\Models\Tr_considers;
class ConsiderUtility
{
    /**
     * 検討中ボタンのスタイル処理
     * @param int $item_id
     */
    public static function makeConsiderButtonStyle($item_id){
      //スタイル初期化
      $class = '';
      $text = '検討する';
      $isConsidering = self::isConsidering($item_id);
      if($isConsidering){
        $class = 'registrated';
        $text = '検討中';
      }
      return array('class' => $class, 'text' => $text);
    }

    /**
     * 検討中案件かどうかの判定
     * @param int $item_id
     */
    public static function isConsidering($item_id){
      if(!FrntUtil::isLogin() && !empty(CkieUtil::get("considers"))){
        $considers = CkieUtil::get("considers");
        if(isset($considers[$item_id]) && $considers[$item_id] === true){
          return true;
        }
      }else{
        $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
        if($cookie){
          $consider = Tr_considers::where("user_id",$cookie)->where("item_id",$item_id)->first();
          if(!empty($consider) && $consider->delete_flag == 0){
            return true;
          }
        }
      }
      return false;
    }

    /**
     * 検討中案件数を計算
     */
    public static function culcConsiderLength(){
      $consider_length = 0;
      if(FrntUtil::isLogin()){
        $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
        if($cookie){
          $considers = Tr_considers::where("user_id",$cookie)->where("delete_flag",0)->get();
          $consider_length = count($considers);
        }
      }else{
        $consider_length = session()->get(SsnUtil::SESSION_KEY_CONSIDERS,0);
        //session()->put(SsnUtil::SESSION_KEY_CONSIDERS,0);
      }
      return $consider_length;
    }

}
