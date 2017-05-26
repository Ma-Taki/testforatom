<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\MailMagazineTopPageRequest;
use App\Http\Requests\admin\MailMagazineRequest;
use App\Libraries\AdminUtility as AdmnUtil;
use App\Libraries\SessionUtility as SsnUtil;
use Mail;
use App\Models\Tr_users;
use App\Models\Tr_mail_magazines;
use App\Models\Tr_link_users_mail_magazines;
use App\Models\Tr_mail_magazines_send_to;
use DB;
use Log;

class MailMagazineController extends Controller
{

    public $target_id; //操作対象となるメルマガID
    public $from_address; //送信元のアドレス
    public $from_address_name; //送信元の名前

    /*
     * 初期化
     */
    public function __construct() {
      $this->target_id = null;
      $admnUtil = new AdmnUtil();
      $this->from_address = $admnUtil->mail_magazine_mail_from;
      $this->from_address_name = $admnUtil->mail_magazine_mail_from_name;
    }

    /*
     * メルマガ管理画面
     * GET:/admin/mail-magazine
     */
    public function index(MailMagazineTopPageRequest $request) {

      //編集モードの場合
      if($request->type=='edit'){
        $itemList = Tr_mail_magazines::where('id',$request->id)->first();
      //新規作成モードの場合
      }else if($request->type=='new'){
        $itemList = null;
      }
      return view('admin.mail_magazine',compact('itemList','request'));
    }

    /*
     * メルマガ保存処理
     * POST:/admin/mail-magazine/store
     */
    public function store(MailMagazineRequest $request) {

        // 送信日時指定の場合
        if ($request->sendDateFlag == AdmnUtil::MAIL_MAGAZINE_SEND_DATE_INPUT){
          $sendtime = date_create($request->sendDateTime);
          $sendtime = date_format($sendtime , 'Y-m-d H:i:s');
        }else{ //即時送信の場合
          $sendtime = date('Y-m-d H:i:s');
        }

        // 送信先アドレス
        $toAddress_array = [];

        //配信希望者のみの場合
        if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_DESIRED_USER) {
            $address= Tr_users::getUserMailMagazine()->get();
            foreach ($address as $key => $value) {
              array_push($toAddress_array,$address[$key]->mail);
            }
        //ユーザー全員の場合
        } else if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_ALL_USER) {
          $address = Tr_users::enable()->get();
            foreach ($address as $key => $value) {
              array_push($toAddress_array,$address[$key]->mail);
            }
        //メールアドレス個別指定の場合
        } else if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_INPUT) {
            $toAddress_array = explode(',', $request->toAddresses);
        }

        // Ccアドレス
        $ccAddress_array = explode(',', $request->ccAddresses);

        // Bccアドレス
        $bccAddress_array = explode(',', $request->bccAddresses);

        // メール送信用データ
        $data_mail = [
            'adminID'          => session(SsnUtil::SESSION_KEY_ADMIN_ID),
            'fromAddress'      => $this->from_address,
            'fromAddressName'  => $this->from_address_name,
            'toAddressArray'   => $toAddress_array,
            'ccAddressArray'   => $ccAddress_array,
            'bccAddressArray'  => $bccAddress_array,
            'addressFlag'      => $request->toAddressesFlag,
            'sendFlag'         => $request->sendDateFlag,
            'subject'          => $request->subject,
            'body'             => $request->mailText,
            'sendTime'         => $sendtime
        ];

        //データベース処理
        DB::transaction(function () use ($data_mail,$request,$toAddress_array){

            //編集モードの場合
            if($request->type=='edit'){

              $this->target_id = $request->id; //操作対象IDを設置

              //mail_magazinesテーブルをアップデート
              Tr_mail_magazines::where('id',$request->id)
                               ->update([
                                 'send_from'=>$data_mail['fromAddress'],
                                 'send_to'=>$data_mail['addressFlag'],
                                 'cc'=>$request->ccAddresses, //データベースへはカンマも含めて保存
                                 'bcc'=>$request->bccAddresses,//データベースへはカンマも含めて保存
                                 'send_flag'=>$data_mail['sendFlag'],
                                 'subject'=>$data_mail['subject'],
                                 'body'=>$data_mail['body'],
                                 'send_at'=>$data_mail['sendTime'],
                                 'delete_flag'=>0
                               ]);

             //中華テーブルをデリート
             Tr_link_users_mail_magazines::where('mail_magazine_id',$request->id)->delete();
             Tr_mail_magazines_send_to::where('mail_magazine_id',$request->id)->delete();

             //中間テーブルをインサート
             foreach ($toAddress_array as $address) {
               //link_users_mail_magazinesテーブルに追加
               $link_users_mail_magazines = new Tr_link_users_mail_magazines;
               $link_users_mail_magazines->mail_magazine_id=$request->id;
               $link_users_mail_magazines->admin_id=$data_mail['adminID'];
               $user = Tr_users::getUserByMail($address)->first();
               $link_users_mail_magazines->user_id=$user ? $user->id : null;
               $link_users_mail_magazines->save();
               //mail_magazines_send_toテーブルに追加
               $mail_magazines_send_to = new Tr_mail_magazines_send_to;
               $mail_magazines_send_to->mail_magazine_id=$request->id;
               $mail_magazines_send_to->admin_id=$data_mail['adminID'];
               $mail_magazines_send_to->mail_address=$address;
               $mail_magazines_send_to->save();
             }

            //新規作成モードの場合
            }else if($request->type=='new'){
              //mail_magazinesテーブルにインサート
              $mail = new Tr_mail_magazines;
              $mail->admin_id=$data_mail['adminID'];
              $mail->send_from=$data_mail['fromAddress'];
              $mail->send_to=$data_mail['addressFlag'];
              $mail->cc=$request->ccAddresses;
              $mail->bcc=$request->bccAddresses;
              $mail->send_flag=$data_mail['sendFlag'];
              $mail->subject=$data_mail['subject'];
              $mail->body=$data_mail['body'];
              $mail->send_at=$data_mail['sendTime'];
              $mail->delete_flag=0;
              $mail->save();

              $this->target_id = $mail->id; //操作対象IDを設置

              //中間テーブルをインサート
              foreach ($toAddress_array as $address) {
                $link_users_mail_magazines = new Tr_link_users_mail_magazines;
                $link_users_mail_magazines->mail_magazine_id=$mail->id;
                $link_users_mail_magazines->admin_id=$data_mail['adminID'];
                $user = Tr_users::getUserByMail($address)->first();
                $link_users_mail_magazines->user_id=$user ? $user->id : null;
                $link_users_mail_magazines->save();

                $mail_magazines_send_to = new Tr_mail_magazines_send_to;
                $mail_magazines_send_to->mail_magazine_id=$mail->id;
                $mail_magazines_send_to->admin_id=$data_mail['adminID'];
                $mail_magazines_send_to->mail_address=$address;
                $mail_magazines_send_to->save();
              }
            }
        });

        //即時送信の場合のみ送信
        if($data_mail['sendFlag'] == AdmnUtil::MAIL_MAGAZINE_SEND_DATE_IMMEDIATELY){
          self::sendMail($data_mail,function($e){
            dump($e);
          },function($id){
            echo "成功";
          });

        }

    }

    /**
     * メルマガ配信停止
     * GET:/admin/mail-magazine/stop
     */
    public function stopMailMagazine(Request $request){

      Tr_mail_magazines::where('id', $request->id)->update(['delete_flag'=>1]);

      return redirect($_SERVER['HTTP_REFERER'])
        ->with('custom_info_messages','メルマガは正常に停止されました');
    }

    /**
     * メルマガ配信開（停止解除）
     * GET:/admin/mail-magazine/start
     */
    public function startMailMagazine(Request $request){

      Tr_mail_magazines::where('id', $request->id)->update(['delete_flag'=>0]);

      return redirect($_SERVER['HTTP_REFERER'])
        ->with('custom_info_messages','メルマガは正常に停止解除されました');
    }

    /**
     * メルマガ送信履歴（検索機能）
     * GET:/admin/mail-magazine/search
     */
    public function search(Request $request){

      $request->flash();

      //データクエリ
      $data_query = array();
      $data_query['subject'] = isset($request->subject) ? self::deleteSpace($request->subject) : '';
      $data_query['body'] = isset($request->body) ? self::deleteSpace($request->body) : '';
      $data_query['send_at0'] = isset($request->send_at0) ? $request->send_at0 : '';
      $data_query['send_at1'] = isset($request->send_at1) ? $request->send_at1 : '';
      $data_query['send_to0'] = isset($request->send_to0) ? $request->send_to0 : '';
      $data_query['send_to1'] = isset($request->send_to1) ? $request->send_to1 : '';
      $data_query['send_to2'] = isset($request->send_to2) ? $request->send_to2 : '';

      //クエリを動的に設定
      $query = Tr_mail_magazines::query();

      //検索[件名]
      if($data_query['subject']!=''){
        $query = $query->where('subject','like','%'.trim($data_query['subject']).'%');
      }

      //検索[本文]
      if($data_query['body']!=''){
        $query = $query->where('body','like','%'.trim($data_query['body']).'%');
      }

      //検索[送信日時]
      $query = $query->where(function($query) use($data_query){
          if($data_query['send_at0']!=''){ //即時
            $query = $query->where('send_flag',$data_query['send_at0']);
          }
          if($data_query['send_at1']!=''){ //指定
            $query = $query->orWhere('send_flag',$data_query['send_at1']);
          }
       });

      //検索[宛先]
      $query = $query->where(function($query) use($data_query){
          if($data_query['send_to0']!=''){ //配信希望者のみ
            $query = $query->where('send_to',$data_query['send_to0']);
          }
          if($data_query['send_to1']!=''){ //ユーザ全員
            $query = $query->orWhere('send_to',$data_query['send_to1']);
          }
          if($data_query['send_to2']!=''){ //メールアドレス指定
            $query = $query->orWhere('send_to',$data_query['send_to2']);
          }
       });

      //新しい順に１ページ１０アイテムで取得
      $itemList = $query->orderBy('id','desc')->paginate(10);

      return view('admin.mail_magazine_search',compact('itemList','data_query'));
    }

    /**
     * メール送信
     */
    public function sendMail($data_mail,$success,$error){

      //１.ステータスを[送信中]に
      Tr_mail_magazines::where('id',$this->target_id)->update(['status'=>1]);

      //２.メール送信
      Mail::send('front.emails.mailmagazine',$data_mail,function ($message) use ($data_mail) {
        $message->from($data_mail['fromAddress'], $data_mail['fromAddressName']);
        $message->to($data_mail['toAddressArray']);
        $message->subject($data_mail['subject']);
        if(trim($data_mail['ccAddressArray'][0])!=''){
          $message->cc($data_mail['ccAddressArray']);
        }
        if(trim($data_mail['bccAddressArray'][0])!=''){
          $message->cc($data_mail['bccAddressArray']);
        }
      });

      //３.ステータスを...
      if(count(Mail::failures()) > 0){
        //送信失敗したら[送信失敗]に
        Tr_mail_magazines::where('id',$this->target_id)->update(['status'=>3]);
        call_user_func($error,Mail::failures());
      }else{
        //送信成功したら[送信済]に
        Tr_mail_magazines::where('id',$this->target_id)->update(['status'=>2]);
        call_user_func($success,$this->target_id);
      }

    }

    //検索文字列の前後空白除去
    public function deleteSpace($str){
      return preg_replace('/^[ 　]+/u', '',$str);
    }


}
