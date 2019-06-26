<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\admin\MailMagazineController;
use App\Models\Tr_users;
use App\Models\Tr_mail_magazines;
use App\Models\Tr_link_users_mail_magazines;
use App\Models\Tr_mail_magazines_send_to;
use App\Libraries\AdminUtility as AdmnUtil;
use Carbon\Carbon;


class SendMailMagazine extends Command
{
    /**
     * コマンド名
     *
     * @var string
     */
    protected $signature = 'mailmagazine:send';

    /**
     * コマンド概要
     *
     * @var string
     */
    protected $description = 'メールマガジンを送信するコマンド';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * コマンド内容
     *
     * @return mixed
     */
    public function handle()
    {
      //mail_magazineテーブルから[送信日時指定あり][ステータスが未送信][現在時刻より過去][削除フラグなし]を取得
      $items = Tr_mail_magazines::where('send_flag',1)->where('status',0)->where('send_at','<=',Carbon::now())->where('delete_flag',0)->get();
      //複数のメルマガをループで送信
      foreach($items as $item){
        //メルマガコントローラー初期化
        $controller = new MailMagazineController;
        //送信対象IDを設置
        $controller->target_id = $item->id;
        //宛先メールアドレス配列
        $toAddress_array = array();
        foreach($item->mailaddresses as $address){
          array_push($toAddress_array,$address->mail_address);
        }
        //Ccメールアドレス配列
        $ccAddress_array = explode(',', $item->cc);
        //Bccメールアドレス配列
        $bccAddress_array = explode(',', $item->bcc);
        //送信データ
        $data_mail = [
          'fromAddress'      => $controller->from_address,
          'fromAddressName'  => $controller->from_address_name,
          'subject'          => $item->subject,
          'body'             => $item->body,
          'toAddressArray'   => $toAddress_array,
          'ccAddressArray'   => $ccAddress_array,
          'bccAddressArray'  => $bccAddress_array,
        ];
        //メール送信
        $controller->sendMail($data_mail,function($error){
          echo "送信失敗";
        },function($sccess){
          echo "送信成功";
        });
      }
    }
}
