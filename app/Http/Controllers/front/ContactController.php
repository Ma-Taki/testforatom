<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\ContactRequest;

class ContactController extends Controller
{
    /**
     * お問い合わせ画面表示
     * GET:/front/contact
     */
    public function index(){
        return view('front.contact_complete');
    }

    /**
     * お問い合わせ内容確認画面表示
     * POST:/front/contact
     **/
    public function store(ContactRequest $request){
        return view('front.contact_confirm', [
            'name' => $request->name,
            'company_name' => $request->company_name,
            'mail' => $request->mail,
            'message' => $request->message
        ]);
    }
}
