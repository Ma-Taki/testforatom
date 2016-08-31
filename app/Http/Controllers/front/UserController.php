<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\UserRegistrationRequest;
use App\Http\Requests\front\UserEditRequest;
use App\Http\Requests\front\EditPasswordRequest;
use App\Http\Requests\front\ReminderRequest;
use App\Http\Requests\front\RecoverPasswordRequest;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\ModelUtility as MdlUtil;
use App\Libraries\CookieUtility as CkieUtil;
use App\Models\Tr_users;
use App\Models\Tr_link_users_contract_types;
use App\Models\Tr_auth_keys;
use Mail;
use Carbon\Carbon;
use DB;
use Log;

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
     * 会員登録処理 & 会員登録完了画面表示
     * POST:/user/regist
     */
    public function store(UserRegistrationRequest $request){

        // prefix_saltを作成
        $prefix_salt = FrntUtil::getPrefixSalt(20);
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
            'password' => $prefix_salt .$request->password .FrntUtil::FIXED_SALT,
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
                    // 本番でデリートする意味はないが、一応
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
        $frntUtil = new FrntUtil();
        Mail::send('front.emails.user_regist', $data, function ($message) use ($data, $frntUtil) {
            $message->from($frntUtil->user_regist_mail_from, $frntUtil->user_regist_mail_from_name);
            $message->to($frntUtil->user_regist_mail_to, $frntUtil->user_regist_mail_to_name);
            $message->subject(FrntUtil::USER_REGIST_MAIL_TITLE);
        });

        return view('front.user_complete')->with('email',  $request->email);
    }

    /*
     * マイページ表示
     * GET:/user
     */
    public function showMyPage(){

        // ログインユーザ情報を取得
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get()
                        ->first();

        if (empty($user)) {
            return redirect('logout');
        }
        return view('front.user_mypage' , compact('user'));
    }

    /*
     * パスワード変更処理
     * POST:/user/edit/password
     */
    public function updatePassword(EditPasswordRequest $request){

        // ログインユーザを取得
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get()
                        ->first();

        // パスワード認証
        $password = md5($user->salt .$request->old_password .FrntUtil::FIXED_SALT);
        if ($user->password != $password) {
            // 認証失敗
            Log::debug('input:'.$password.' db:'.$user->password);
            return back()->with('custom_error_messages',['現在のパスワードに誤りがあります。'])->withInput();
        }
        // 認証成功
        // prefix_saltを作成
        $prefix_salt = FrntUtil::getPrefixSalt(20);
        $new_password = md5($prefix_salt .$request->new_password .FrntUtil::FIXED_SALT);

        $db_data = [
            'salt' => $prefix_salt,
            'new_password' => $new_password,
        ];

        // トランザクション
        DB::transaction(function () use ($user, $db_data) {
            try {
                // ユーザーをアップデート
                $user->salt = $db_data['salt'];
                $user->password = $db_data['new_password'];
                $user->save();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/user');
    }

    /*
     * 退会処理
     * POST:/user/delete
     */
    public function deleteUser(){

        // ログインユーザを取得
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get()
                        ->first();

        $db_data = [
            'user' => $user,
            'delete_date' => Carbon::today()->format('Y-m-d'),
        ];

        // トランザクション
        DB::transaction(function () use ($db_data) {
            try {
                // ユーザーをアップデート
                $db_data['user']->delete_flag = $db_data['user']->id;
                $db_data['user']->delete_date = $db_data['delete_date'];
                $db_data['user']->save();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/logout');
    }

    /*
     * パスワード再設定URL送信処理
     * POST:/user/reminder
     */
    public function sendReminder(ReminderRequest $request){

        // メールアドレスからユーザを取得
        $user = Tr_users::where('mail', $request->mail)
                        ->enable()
                        ->get()
                        ->first();

        if (empty($user)) {
            return back()->with('custom_error_messages',['登録されていないメールアドレスです。'])->withInput();
        }

        $auth_key = Tr_auth_keys::where('user_id', $user->id)
                                ->where('auth_task', MdlUtil::AUTH_TASK_RECOVER_PASSWORD)
                                ->get()
                                ->first();

        // UUIDの生成、衝突をチェック（16^40通りなので、まず衝突しない）
        $ticket = '';
        do {
            if (!empty($ticket)) Log::info('[duplicate UUID] ticket:'.$ticket);
            $ticket = sha1(uniqid(rand(), true));
            $w_obj = Tr_auth_keys::where('auth_task', $ticket)->get()->first();
        } while (!empty($w_obj));

        if (empty($auth_key)) {
            $auth_key = new Tr_auth_keys;
            $auth_key->user_id = $user->id;
            $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
            $auth_key->auth_task = MdlUtil::AUTH_TASK_RECOVER_PASSWORD;
            $auth_key->ticket = $ticket;
            $auth_key->save();

        } else {
            $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
            $auth_key->ticket = $ticket;
            $auth_key->save();
        }

        // メール送信
        $mail_data = [
            'auth_key' => $auth_key,
            'limit' => FrntUtil::AUTH_KEY_LIMIT_MINUTE,
        ];

        $frntUtil = new FrntUtil();
        Mail::send('front.emails.user_reminder', $mail_data, function ($message) use ($frntUtil) {
            $message->from($frntUtil->user_reminder_mail_from, $frntUtil->user_reminder_mail_from_name);
            $message->to($frntUtil->user_reminder_mail_to, $frntUtil->user_reminder_mail_to_name);
            $message->subject(FrntUtil::USER_REMINDER_MAIL_TITLE);
        });

        return view('front.user_reminder_complete');
    }

    /*
     * パスワード再設定画面
     * GET:/user/recovery
     */
    public function showRecovery(Request $request){

        $auth_key = Tr_auth_keys::where('id', $request->id)
                                ->where('ticket', $request->ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_RECOVER_PASSWORD)
                                ->where('application_datetime', '<=' ,Carbon::now()->addHour()->format('Y-m-d H:i:s'))
                                ->get()
                                ->first();

        if (empty($auth_key)) {
            Log::warning('['.__METHOD__ .'#'.__LINE__.'] entity not found(Tr_auth_keys)',[
                'auth_id' => $request->id,
                'ticket' =>  $request->ticket,
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        return view('front.recover_password', [
            'id' => $request->id,
            'ticket' => $request->ticket,
        ]);
    }

    /*
     * パスワード再設定処理 & 完了画面表示
     * POST:/user/recovery
     */
    public function recoverPassword(RecoverPasswordRequest $request){

        $auth_key = Tr_auth_keys::where('id', $request->id)
                                ->where('ticket', $request->ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_RECOVER_PASSWORD)
                                ->where('application_datetime', '>=' ,Carbon::now()->subHour()->format('Y-m-d H:i:s'))
                                ->get()
                                ->first();

        if (empty($auth_key)) {
            Log::warning('['.__METHOD__ .'#'.__LINE__.'] entity not found(Tr_auth_keys)',[
                'auth_id' => $request->id,
                'ticket' =>  $request->ticket,
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        $user = Tr_users::where('id', $auth_key->user_id)
                         ->enable()
                         ->get()
                         ->first();

        if (empty($user)) {
            Log::warning('['.__METHOD__ .'#'.__LINE__.'] entity not found(Tr_users)',[
                'auth_id' => $request->id,
                'user_id' =>  $auth_key->user_id,
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        // パスワード作成
        $prefix_salt = FrntUtil::getPrefixSalt(20);
        $new_password = md5($prefix_salt .$request->new_password .FrntUtil::FIXED_SALT);

        $db_data = [
            'salt' => $prefix_salt,
            'new_password' => $new_password,
        ];

        // トランザクション
        DB::transaction(function () use ($auth_key, $user, $db_data) {
            try {
                // ユーザーをアップデート
                $user->salt = $db_data['salt'];
                $user->password = $db_data['new_password'];
                $user->save();

                // 認証キーは物理削除
                $auth_key->delete();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        Log::info('password recovery success',['user_id' => $user->id]);

        return view('front.recover_password_complete');
    }

    /*
     * プロフィール変更画面表示
     * GET:/user/edit
     */
    public function showUserEdit(){

        // ログインユーザを取得
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get()
                        ->first();

        return view('front.user_edit', compact('user'));
    }

    /*
     * プロフィール変更処理
     * POST:/user/edit
     */
    public function updateUser(UserEditRequest $request){

        // ログインユーザを取得
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get()
                        ->first();

        if (empty($user)) {
            Log::warning('['.__METHOD__ .'#'.__LINE__.'] entity not found(Tr_users)',[
                'user_id' => CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID),
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        $db_data = [
            'user' => $user,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'last_name_kana' => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'gender' => $request->gender,
            'birth' => date('Y-m-d', strtotime($request->birth)),
            'education' => $request->education,
            'country' => $request->country,
            'prefecture_id' => $request->prefecture_id,
            'station' => $request->station,
            'email' => $request->email,
            'phone_num' => $request->phone_num,
            'contract_types' => $request->contract_types,
        ];

        // トランザクション
        DB::transaction(function () use ($db_data) {
            try {
                // ユーザーテーブルにインサート
                // 必須項目以外は、入力されていない場合nullを指定
                // empty()で判別しているため、'0'は空文字扱い
                $db_data['user']->first_name = $db_data['first_name'];
                $db_data['user']->last_name = $db_data['last_name'];
                $db_data['user']->first_name_kana = $db_data['first_name_kana'];
                $db_data['user']->last_name_kana = $db_data['last_name_kana'];
                $db_data['user']->sex = $db_data['gender'];
                $db_data['user']->birth_date = $db_data['birth'];
                $db_data['user']->education_level = !empty($db_data['education']) ? $db_data['education'] : null;
                $db_data['user']->nationality = !empty($db_data['country']) ? $db_data['country'] : null;
                $db_data['user']->prefecture_id = $db_data['prefecture_id'];
                $db_data['user']->station = !empty($db_data['station']) ? $db_data['station'] : null;
                $db_data['user']->mail = $db_data['email'];
                $db_data['user']->tel = $db_data['phone_num'];
                $db_data['user']->save();

                // ユーザ契約形態中間テーブルをデリートインサート
                Tr_link_users_contract_types::where('user_id', $db_data['user']->id)->delete();
                foreach ((array)$db_data['contract_types'] as $contract_type) {
                    Tr_link_users_contract_types::create([
                        'user_id' => $db_data['user']->id,
                        'contract_type_id' => $contract_type,
                    ]);
                }

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/user');
    }
}
