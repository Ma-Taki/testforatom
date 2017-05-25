<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\admin\MailMagazineController;
use App\Models\Tr_users;
use App\Models\Tr_mail_magazines;
use App\Models\Tr_link_users_mail_magazines;
use App\Models\Tr_mail_magazines_send_to;
use App\Libraries\AdminUtility as AdmnUtil;


class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send MailMagazine To Users';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $item = Tr_mail_magazines::where('send_flag',1)->where('status',0))->orderBy('id','asc');

      $admnUtil = new AdmnUtil();
      $from_address = $admnUtil->mail_magazine_mail_from;
      $from_address_name = $admnUtil->mail_magazine_mail_from_name;

      $toAddress_array = array();

      foreach($item->mailaddresses as $address){
        array_push($toAddress_array,$address->mail_address);
      }

      $ccAddress_array = explode(',', $item->cc);
      $bccAddress_array = explode(',', $item->bcc);

      // メール送信用データ
      $data_mail = [
          'fromAddress'      => $from_address,
          'fromAddressName'  => $from_address_name,
          'subject'          => $item->subject,
          'body'             => $item->body,
          'toAddressArray'   => $toAddress_array,
          'ccAddressArray'   => $ccAddress_array,
          'bccAddressArray'  => $bccAddress_array,
      ];

      $controller = new MailMagazineController;
      $controller->sendMail($data_mail);
    }
}
