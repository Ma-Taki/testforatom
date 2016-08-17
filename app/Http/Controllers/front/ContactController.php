<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\ContactRequest;
use App\Libraries\FrontUtility as FrontUtil;
use Mail;
use Carbon\Carbon;

class ContactController extends Controller
{
    /**
     * お問い合わせ画面表示
     * GET:/front/contact
     */
    public function index(Request $request){
        return view('front.contact');
    }

    /**
     * お問い合わせ画面修正表示
     * POST:/front/contact
     **/
    public function store(Request $request){
        return view('front.contact', $request->all());
    }

    /**
     * お問い合わせ内容確認画面表示
     * POST:/front/contact/confirm
     **/
    public function confirm(ContactRequest $request){
        return view('front.contact_confirm', $request->all());
    }

    /**
     * お問い合わせ処理・完了画面表示
     * POST:/front/contact/complete
     **/
    public function complete(Request $request){

        $data = [
            'user_name' => $request->last_name.' '.$request->first_name.'（'.$request->last_name_kana.' '.$request->first_name_kana.'）',
            'company_name' => $request->company_name,
            'mail' => $request->email,
            'contactMessage' => $request->contactMessage,
            'date' => Carbon::now()->toDateTimeString()
        ];
        $frontUtil = new FrontUtil();
        Mail::send('front.emails.contact', $data, function ($message) use ($data, $frontUtil) {
            $message->from($frontUtil->user_contact_mail_from, $frontUtil->user_contact_mail_from_name);
            $message->to($frontUtil->user_contact_mail_to, $frontUtil->user_contact_mail_to_name);
            $message->subject(FrontUtil::USER_CONTACT_MAIL_TITLE);
        });

        return view('front.contact_complete');
    }


}
