<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\ContactRequest;
use Mail;

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

        return view('front.contact', [
            'user_name' => $request->user_name,
            'company_name' => $request->company_name,
            'mail' => $request->mail,
            'mail_confirmation' => $request->mail_confirmation,
            'contactMessage' => $request->contactMessage
        ]);
    }

    /**
     * お問い合わせ内容確認画面表示
     * POST:/front/contact/confirm
     **/
    public function confirm(ContactRequest $request){

        return view('front.contact_confirm', [
            'user_name' => $request->user_name,
            'company_name' => $request->company_name,
            'mail' => $request->mail,
            'contactMessage' => $request->contactMessage
        ]);
    }

    /**
     * お問い合わせ処理・完了画面表示
     * POST:/front/contact/complete
     **/
    public function contact(Request $request){

        $subject = '【エンジニアルート】お問い合わせメール';
        $body = 'エンジニアルート-お問い合わせメール';
        $body .= "\n";
        $body .= '【問い合わせ日時】';
        $body .= '【氏名】' .$request->user_name;
        $body .= '【会社名】' .$request->company_name;
        $body .= '【メールアドレス】' .$request->mail;
        $body .= '【お問い合わせ内容】';
        $body .= $request->contactMessage;
        $header = 'From: contact@engineer-route.solidseed.jp';

        mail('y.suzuki@solidseed.co.jp', $subject, $body, $header);

        return view('front.contact_complete');
    }


}
