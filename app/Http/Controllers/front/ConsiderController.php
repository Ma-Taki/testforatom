<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontController;
use App\Http\Requests;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\CookieUtility as CkieUtil;
use Input;
use Carbon\Carbon;
use DB;
use App\Models\Tr_considers;
use App\Models\Tr_users;
use App\Models\Tr_items;


class ConsiderController extends FrontController
{

  /**
   * 検討中案件一覧表示
   */

  public function showConsiderItems(Request $request)
 {

   $itemList = array();

   if(FrntUtil::isLogin()){
     $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
     if(!empty($cookie)){
       $considers = Tr_considers::where("user_id",$cookie)->where("delete_flag",0)->get();
       foreach($considers as $consider){
         array_push($itemList,Tr_items::where('id',$consider->item_id)->get()->first());
       }
     }
   }else{
     if(!empty(CkieUtil::get("considers"))){
       foreach(CkieUtil::get("considers") as $key => $value){
         array_push($itemList,Tr_items::where('id',$key)->get()->first());
       }
      }
   }
   return view('front.considers_list', compact('itemList'));
 }


  /**
   * 検討中リストに登録
   */

  public function ajaxRegister(Request $request)
 {
   //ログインしている時
   if(FrntUtil::isLogin()){
     //クッキーからuser_id取得
     $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
     $user = Tr_users::find($cookie);
     //すでに検討中かデータベース検索（user_idとitem_idの複合インデックス）
     $considers = Tr_considers::where('user_id',$user->id)->where('item_id',$request->item_id);
     //すでに検討中であればアップデート
     if($considers->count() > 0){
       $considers->update(['user_id' => $user->id,'item_id'=>$request->item_id,'delete_flag'=>0]);
     //まだ検討中でなければインサート
     }else{
       $csd = new Tr_considers;
       $csd->user_id = $user->id;
       $csd->item_id = $request->item_id;
       $csd->delete_flag = 0;
       $csd->save();
     }
   //ログインしていなければクッキーに追加
   }else{
     CkieUtil::set("considers[$request->item_id]",true);
   }
 }

 /**
  * 検討中リストから削除
  */

 public function ajaxDelete(Request $request)
{
  //ログインしている時
  if(FrntUtil::isLogin()){
    //クッキーからuser_id取得
    $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
    $user = Tr_users::find($cookie);
    $considers = Tr_considers::where('user_id',$user->id)->where('item_id',$request->item_id)->update(['delete_flag'=>1]);
  //ログインしていなければクッキーから削除
  }else{
    CkieUtil::delete('considers['.$request->item_id.']');
  }
}

 /**
  * 検討中ボタンのスタイル処理
  * 引数:案件id
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
  */
 public static function isConsidering($item_id){
   //ログインしていない時
   if(!FrntUtil::isLogin() && !empty(CkieUtil::get("considers"))){
     $considers = CkieUtil::get("considers");
     if(isset($considers[$item_id]) && $considers[$item_id] === true){
       return true;
     }
   //ログインしている時
   }else{
     $cookie = \Cookie::get(CkieUtil::COOKIE_NAME_PREFIX .CkieUtil::COOKIE_NAME_USER_ID);
     if(!empty($cookie)){
       $considers = Tr_considers::where("user_id",$cookie)->where("item_id",$item_id)->first();
       if(!empty($considers) && $considers->delete_flag === 0){
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
       $user = Tr_considers::where("user_id",$cookie)->where("delete_flag",0)->get();
       $consider_length = count($user);
     }
   }else{
     $consider_length = CkieUtil::get("considers") ? count(CkieUtil::get("considers")) : 0;
   }
   return $consider_length;
 }

}
