<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontController;
use App\Http\Requests;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\CookieUtility as CkieUtil;
use App\Libraries\ConsiderUtility as CnsUtil;
use App\Libraries\SessionUtility as SsnUtil;
use App\Models\Tr_considers;
use App\Models\Tr_users;
use App\Models\Tr_items;

use Monolog\Handler\RotatingFileHandler;

class ConsiderController extends FrontController
{

  /**
   * 検討中案件一覧表示
   */

  public function showConsiderItems(Request $request)
 {

   $filename = storage_path('logs/laravel-testlog.log');
   $handler = new Monolog\Handler\RotatingFileHandler($filename);
   $monolog -> pushHandler($handler);

   var_dump($monolog);



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
     echo json_encode(CnsUtil::culcConsiderLength());
   //ログインしていなければクッキーに追加（有効期間２ヶ月）
   }else{
     CkieUtil::set("considers[$request->item_id]",true,CkieUtil::COOKIE_TIME_MONTH*2);
     //案件数カウントだけセッションで管理
     $cnt = session()->get(SsnUtil::SESSION_KEY_CONSIDERS,0);
     session()->put(SsnUtil::SESSION_KEY_CONSIDERS,$cnt+1);
     echo json_encode($cnt+1);
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
      echo json_encode(CnsUtil::culcConsiderLength());
    //ログインしていなければクッキーから削除
    }else{
      CkieUtil::delete('considers['.$request->item_id.']');
      //案件数カウントだけセッションで管理
      $cnt = session()->get(SsnUtil::SESSION_KEY_CONSIDERS,0);
      $cnt>0 ? $cnt -- : 0;
      session()->put(SsnUtil::SESSION_KEY_CONSIDERS,$cnt);
      echo json_encode($cnt);
    }
  }

}
