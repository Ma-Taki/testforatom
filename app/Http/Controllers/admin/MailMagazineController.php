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
    /*
     * メルマガ管理画面
     * GET:/admin/mail-magazine
     */
    public function index(MailMagazineTopPageRequest $request) {

      //編集
      if($request->type=='edit'){
        $itemList = Tr_mail_magazines::where('id',$request->id)->first();
      //新規作成
      }else if($request->type=='new'){
        $itemList = null;
      }
      return view('admin.mail_magazine',compact('itemList','request'));
    }

    /*
     * メルマガ送信処理
     * POST:/admin/mail-magazine
     */
    public function store(MailMagazineRequest $request) {

        // Fromアドレス
        $admnUtil = new AdmnUtil();
        $from_address = $admnUtil->mail_magazine_mail_from;
        $from_address_name = $admnUtil->mail_magazine_mail_from_name;

        // メール送信日時
        if ($request->sendDateFlag == AdmnUtil::MAIL_MAGAZINE_SEND_DATE_INPUT){ //指定あり
          $sendtime = date_create($request->sendDateTime);
          $sendtime = date_format($sendtime , 'Y-m-d H:i:s');
        }else{ //即時
          $sendtime = date('Y-m-d H:i:s');
        }

        // Toアドレス
        $toAddress_array = [];
        //ユーザー希望者のみ
        if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_DESIRED_USER) {
            $address= Tr_users::getUserMailMagazine()->get();
            foreach ($address as $key => $value) {
              array_push($toAddress_array,$address[$key]->mail);
            }
        //ユーザー全員
        } else if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_ALL_USER) {
          $address = Tr_users::enable()->get();
            foreach ($address as $key => $value) {
              array_push($toAddress_array,$address[$key]->mail);
            }
        //メールアドレスを指定する
        } else if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_INPUT) {
            $toAddress_array = explode(',', $request->toAddresses);

        } else {
            // エラー
        }

        // Ccアドレス
        $ccAddress_array = explode(',', $request->ccAddresses);

        // Bccアドレス
        $bccAddress_array = explode(',', $request->bccAddresses);

        // メール送信用データ
        $data_mail = [
            'adminID'          => session(SsnUtil::SESSION_KEY_ADMIN_ID),
            'fromAddress'      => $from_address,
            'fromAddressName'  => $from_address_name,
            'toAddressArray'   => $toAddress_array,
            'ccAddressArray'   => $ccAddress_array,
            'bccAddressArray'  => $bccAddress_array,
            'addressFlag'      => $request->toAddressesFlag,
            'sendFlag'         => $request->sendDateFlag,
            'subject'          => $request->subject,
            'body'             => $request->mailText,
            'sendTime'         => $sendtime
        ];

        //データベース
        DB::transaction(function () use ($data_mail,$request,$toAddress_array){
            //編集の場合はUPDATE
            if($request->type=='edit'){
              //lin_users_mail_magazinesテーブルを更新
              Tr_mail_magazines::where('id',$request->id)
                               ->update([
                                 'send_from'=>$data_mail['fromAddress'],
                                 'send_to'=>$data_mail['addressFlag'],
                                 'cc'=>$request->ccAddresses,
                                 'bcc'=>$request->bccAddresses,
                                 'send_flag'=>$data_mail['sendFlag'],
                                 'subject'=>$data_mail['subject'],
                                 'body'=>$data_mail['body'],
                                 'send_at'=>$data_mail['sendTime'],
                                 'delete_flag'=>0
                               ]);

             //まずlink_users_mail_magazinesテーブルとTr_mail_magazines_send_toテーブルを削除
             Tr_link_users_mail_magazines::where('mail_magazine_id',$request->id)->delete();
             Tr_mail_magazines_send_to::where('mail_magazine_id',$request->id)->delete();

             //link_users_mail_magazinesテーブルとTr_mail_magazines_send_toテーブルを追加
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

          //新規作成の場合はINSERT
            }else if($request->type=='new'){
              //mail_magazinesテーブルに追加
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

              foreach ($toAddress_array as $address) {
                //link_users_mail_magazinesテーブルに追加
                $link_users_mail_magazines = new Tr_link_users_mail_magazines;
                $link_users_mail_magazines->mail_magazine_id=$mail->id;
                $link_users_mail_magazines->admin_id=$data_mail['adminID'];
                $user = Tr_users::getUserByMail($address)->first();
                $link_users_mail_magazines->user_id=$user->id;
                $link_users_mail_magazines->save();
                //mail_magazines_send_toテーブルに追加
                $mail_magazines_send_to = new Tr_mail_magazines_send_to;
                $mail_magazines_send_to->mail_magazine_id=$mail->id;
                $mail_magazines_send_to->admin_id=$data_mail['adminID'];
                $mail_magazines_send_to->mail_address=$address;
                $mail_magazines_send_to->save();
              }
            }
        });

        //即時送信の場合は今すぐ送信
        //if($data_mail['addressFlag'] == AdmnUtil::MAIL_MAGAZINE_SEND_DATE_IMMEDIATELY){
          Mail::send('front.emails.mail_auth',$data_mail,function ($message) use ($data_mail, $admnUtil) {
            $message->from($admnUtil->mail_magazine_mail_from, $admnUtil->mail_magazine_mail_from_name);
            $message->to($data_mail['toAddressArray']);
            $message->subject($data_mail['subject']);
            $message->cc($data_mail['ccAddressArray']);
            $message->bcc($data_mail['bccAddressArray']);
          });
        //}

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
     * メルマガ配信開始
     * GET:/admin/mail-magazine/start
     */
    public function startMailMagazine(Request $request){

      Tr_mail_magazines::where('id', $request->id)->update(['delete_flag'=>0]);

      return redirect($_SERVER['HTTP_REFERER'])
        ->with('custom_info_messages','メルマガは正常に配信開始されました');
    }

    /**
     * メルマガ送信履歴および検索
     * GET:/admin/mail-magazine/search
     */
    public function search(Request $request){

      $request->flash();

      $data_query = self::setDataQuery($request);

      $query = Tr_mail_magazines::query();

      if($data_query['subject']!=''){
        $query = $query->where('subject','like','%'.trim($data_query['subject']).'%');
      }

      if($data_query['body']!=''){
        $query = $query->where('body','like','%'.trim($data_query['body']).'%');
      }

      $query = $query->where(function($query) use($data_query){
          if($data_query['send_at0']!=''){
            $query = $query->where('send_flag',$data_query['send_at0']);
          }
          if($data_query['send_at1']!=''){
            $query = $query->orWhere('send_flag',$data_query['send_at1']);
          }
       });

      $query = $query->where(function($query) use($data_query){
          if($data_query['send_to0']!=''){
            $query = $query->where('send_to',$data_query['send_to0']);
          }
          if($data_query['send_to1']!=''){
            $query = $query->orWhere('send_to',$data_query['send_to1']);
          }
          if($data_query['send_to2']!=''){
            $query = $query->orWhere('send_to',$data_query['send_to2']);
          }
       });

      $itemList = $query->orderBy('id','desc')->paginate(10);

      return view('admin.mail_magazine_search',compact('itemList','data_query'));
    }

    //検索文字列の前後空白除去
    public function deleteSpace($str){
      return preg_replace('/^[ 　]+/u', '',$str);
    }

    //$requestをセット
    public function setDataQuery($request){

      $data_query = array();
      $data_query['subject'] = isset($request->subject) ? self::deleteSpace($request->subject) : '';
      $data_query['body'] = isset($request->body) ? self::deleteSpace($request->body) : '';
      $data_query['send_at0'] = isset($request->send_at0) ? $request->send_at0 : '';
      $data_query['send_at1'] = isset($request->send_at1) ? $request->send_at1 : '';
      $data_query['send_to0'] = isset($request->send_to0) ? $request->send_to0 : '';
      $data_query['send_to1'] = isset($request->send_to1) ? $request->send_to1 : '';
      $data_query['send_to2'] = isset($request->send_to2) ? $request->send_to2 : '';

      return $data_query;

    }

}
