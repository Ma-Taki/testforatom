<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\FrontController;
use App\Http\Requests\front\CompanyRequest;
use App\Libraries\FrontUtility as FrontUtil;
use Mail;
use Carbon\Carbon;

class CompanyController extends FrontController
{
    /**
     * 企業の皆様へ画面表示
     * GET:/company
     */
    public function index(){
        return view('front.company');
    }

    /**
     * 企業の皆様へ画面修正表示
     * POST:/company
     **/
    public function store(Request $request){
        return view('front.company', $request->all());
    }

    /**
     * 企業の皆様へ確認画面表示
     * POST:/company/confirm
     **/
    public function confirm(CompanyRequest $request){
        return view('front.company_confirm', $request->all());
    }

    /**
     * お問い合わせ処理・完了画面表示
     * POST:/company/complete
     **/
    public function complete(Request $request){

        $data = [
            'date' => Carbon::now()->toDateTimeString(),
            'contact_type_name' => FrontUtil::COMPANY_CONTACT_TYPE[$request->contact_type],
            'user_name' => $request->last_name.' '.$request->first_name.'（'.$request->last_name_kana.' '.$request->first_name_kana.'）',
            'company_name' => $request->company_name,
            'department_name' => $request->department_name,
            'address' => $request->post_num .' ' .$request->address,
            'phone_num' => $request->phone_num,
            'mail' => $request->mail,
            'url' => $request->url,
            'contactMessage' => $request->contactMessage,
        ];

        // メール送信
        $frontUtil = new FrontUtil();
        Mail::send('front.emails.company_contact', $data, function ($message) use ($data, $frontUtil) {
            $message->from($frontUtil->company_contact_mail_from, $frontUtil->company_contact_mail_from_name);
            foreach ((array)$frontUtil->company_contact_mail_to as $value) {
                $message->to($value, $frontUtil->company_contact_mail_to_name);
            }
            $message->subject(FrontUtil::COMPANY_CONTACT_MAIL_TITLE);
        });

        return view('front.company_complete');
    }
}
