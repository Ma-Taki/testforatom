<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\UserRegistrationRequest;
use App\Libraries\FrontUtility as FrontUtil;
use App\Models\Tr_users;
use Mail;

class UserController extends Controller
{
    /**
     * 新規会員登録画面表示
     * GET:/user/regist/input
     */
    public function index(){
        return view('front.user_input');
    }

    /**
     * 新規会員登録画面表示
     * POST:/user/regist/input
     */
    public function store(){
        return view('front.user_input');
    }

    /**
     * 会員登録処理&会員登録完了画面表示
     * POST:/user/regist/complete
     * UserRegistrationRequest
     */
    public function insertUser(Request $request){

        $db_data = [
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'gender' => $request->gender,
            'birth' => date('Y-m-d', strtotime($request->birth)),
            'education' => $request->education,
            'country' => $request->country,
            'contract_types' => $request->contract_types,
            'prefectures' => $request->prefectures,
            'station' => $request->station,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        // トランザクション
        /*
        DB::transaction(function () use ($admin_name, $login_id, $password, $authList, $timestamp) {
            try {
                // 管理者テーブルにインサート
                $user = Tr_admin_user::create([
                    'admin_name' => $admin_name,
                    'login_id' => $login_id,
                    'password' => $password,
                    'registration_date' => date('Y-m-d H:i:s', $timestamp),
                    'last_update_date' => date('Y-m-d H:i:s', $timestamp),
                    'last_login_date' => date('Y-m-d H:i:s', $timestamp),
                    'delete_flag' => false,
                ]);

                // 管理者権限中間テーブルにインサート
                foreach ((array)$authList as $auth) {
                    Tr_link_admin_user_admin_auth::create([
                        'admin_id' => $user->id,
                        'auth_id' => $auth,
                    ]);
                }
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        */


        // メール送信
        $data = [
            'user_name' => $request->last_name .$request->first_name,
            'email' => $request->email,
        ];
        $frontUtil = new FrontUtil();
        Mail::send('front.emails.user_regist', $data, function ($message) use ($data, $frontUtil) {
            $message->from($frontUtil->user_regist_mail_from, $frontUtil->user_regist_mail_from_name);
            $message->to($frontUtil->user_regist_mail_to, $frontUtil->user_regist_mail_to_name);
            $message->subject(FrontUtil::USER_REGIST_MAIL_TITLE);
        });

        return view('front.user_complete')->with('email',  $request->email);
    }


}
