<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\MailMagazineRequest;
use App\Libraries\AdminUtility as AdmnUtil;
use Mail;
use App\Models\Tr_users;

class MailMagazineController extends Controller
{
    /*
     * メルマガ管理画面
     * GET:/admin/mail-magazine
     */
    public function index() {
        return view('admin.mail_magazine');
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
        if ($request->) {
            # code...
        }

        // Toアドレス
        $toAddress_array = [];
        if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_DESIRED_USER) {
            $toAddress_array = Tr_users::getMailDesiredUser();

        } else if ($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_ALL_USER) {
            $toAddress_array = Tr_users::enable();

        } else if (($request->toAddressesFlag == AdmnUtil::MAIL_MAGAZINE_TO_INPUT) {
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
            'fromAddress'      => $from_address,
            'fromAddressName'  => $from_address_name,
            'toAddressArray'   => $toAddress_array,
            'ccAddressArray'   => $ccAddress_array,
            'bccAddressArray'  => $bccAddress_array,
            'subject'          => $request->subject,
        ];

        // メール送信
        Mail::raw($request->mailText , function ($message) use ($data_mail, $admnUtil) {
            $message->from($admnUtil->mail_magazine_mail_from, $admnUtil->mail_magazine_mail_from_name);
            $message->to($data_mail['toAddresses']);
            $message->subject($data_mail['subject']);
        });

        return view('admin.mail_magazine');
    }
}
