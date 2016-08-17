<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\UserRegistrationRequest;
use App\Libraries\FrontUtility as FrontUtil;
use App\Models\Tr_users;
use App\Models\Tr_link_users_contract_types;
use Mail;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{
    /**
     * 新規会員登録画面表示
     * GET:/user/regist
     */
    public function index(){
        return view('front.user_input');
    }

    /**
     * 会員登録処理&会員登録完了画面表示
     * POST:/user/regist]
*
     */
    public function store(UserRegistrationRequest $request){

        // prefix_saltを作成
        $prefix_salt = $this->getPrefixSalt(20);
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
            'prefecture_id' => $request->prefecture_id,
            'station' => $request->station,
            'email' => $request->email,
            'phone_num' => $request->phone_num,
            'salt' => $prefix_salt,
            'password' => $prefix_salt .$request->password .FrontUtil::FIXED_SALT,
            'now' => Carbon::now()->format('Y-m-d H:i:s'),
            'contract_types' => $request->contract_types,
        ];

        // トランザクション
        DB::transaction(function () use ($db_data) {
            try {
                // ユーザーテーブルにインサート
                // 必須項目以外は、入力されていない場合nullを指定
                // empty()で判別しているため、'0'は空文字扱い
                $user = new Tr_users;
                $user->salt = $db_data['salt'];
                $user->password = md5($db_data['password']);
                $user->first_name = $db_data['first_name'];
                $user->last_name = $db_data['last_name'];
                $user->first_name_kana = $db_data['first_name_kana'];
                $user->last_name_kana = $db_data['last_name_kana'];
                $user->registration_date = $db_data['now'];
                $user->last_login_date = $db_data['now'];
                $user->sex = $db_data['gender'];
                $user->birth_date = $db_data['birth'];
                $user->education_level = !empty($db_data['education']) ? $db_data['education'] : null;
                $user->nationality = !empty($db_data['country']) ? $db_data['country'] : null;
                $user->prefecture_id = $db_data['prefecture_id'];
                $user->station = !empty($db_data['station']) ? $db_data['station'] : null;
                $user->mail = $db_data['email'];
                $user->tel = $db_data['phone_num'];
                $user->delete_flag = 0;
                $user->delete_date = null;
                $user->version = 0;
                $user->save();

                // ユーザ契約形態中間テーブルにインサート
                foreach ((array)$db_data['contract_types'] as $contract_type) {
                    // 本番でデリートする意味はない
                    Tr_link_users_contract_types::where('user_id', $user->id)->delete();
                    Tr_link_users_contract_types::create([
                        'user_id' => $user->id,
                        'contract_type_id' => $contract_type,
                    ]);
                }

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

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

    /**
     * ASCIIコードが32~126の範囲でランダムな文字列を作成する。
     * 引数は文字数。
     * @param int $len
     * @return String
     */
    private function getPrefixSalt($len){
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $rand_int = mt_rand(32,126);
            $str .= chr($rand_int);
        }
        return $str;
    }
}
