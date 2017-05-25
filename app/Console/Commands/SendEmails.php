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

      $items = Tr_mail_magazines::where('send_flag',0)->where('status',2)->where('send_at','<=',Carbon::now())->where('delete_flag',0)->get();
      foreach($items as $item){
        $controller = new MailMagazineController;
        $controller->id = $item->id;
        $toAddress_array = array();
        foreach($item->mailaddresses as $address){
          array_push($toAddress_array,$address->mail_address);
        }
        $ccAddress_array = explode(',', $item->cc);
        $bccAddress_array = explode(',', $item->bcc);
        $data_mail = [
            'fromAddress'      => $controller->from_address,
            'fromAddressName'  => $controller->from_address_name,
            'subject'          => $item->subject,
            'body'             => $item->body,
            'toAddressArray'   => $toAddress_array,
            'ccAddressArray'   => $ccAddress_array,
            'bccAddressArray'  => $bccAddress_array,
        ];
        $controller->sendMail($data_mail);
      }
    }
}
