<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\front\UserRegistrationRequest;
use App\Http\Requests\front\UserEditRequest;
use App\Http\Requests\front\EditPasswordRequest;
use App\Http\Requests\front\EditEmailRequest;
use App\Http\Requests\front\ReminderRequest;
use App\Http\Requests\front\RecoverPasswordRequest;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\ModelUtility as MdlUtil;
use App\Libraries\CookieUtility as CkieUtil;
use App\Libraries\SessionUtility as SsnUtil;
use App\Models\Tr_users;
use App\Models\Tr_link_users_contract_types;
use App\Models\Tr_auth_keys;
use App\Models\Tr_user_social_accounts;
use App\Models\Tr_item_entries;
use Mail;
use Storage;
use Carbon\Carbon;
use DB;
use Log;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Socialite;

class UserController extends Controller
{
    /**
     * メールアドレス認証 & 完了画面表示
     * GET:/user/regist/comp
     */
    public function registComp(Request $request) {

        $ticket = $request->ticket;
        $auth_key = Tr_auth_keys::where('ticket', $ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                                ->get()
                                ->first();

        // 認証鍵が存在しない場合
        if (empty($auth_key)) {
            $this->log('error', 'entity not found(Tr_auth_keys)', [
                'ticket' => $ticket,
                'auth_task' => MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT,
            ]);
            return redirect('/user/regist')->with('custom_error_messages',['認証済みメールアドレスがありません。']);
        }

        // 24時間以内のアクセスかチェック
        $limit = $auth_key->application_datetime->addDay();
        $now = Carbon::now();
        if (!$now->lte($limit)) {

            $this->log('error', 'limit over mail auth', [
                'regist' => $auth_key->application_datetime,
                'limit' => $limit,
                'now' => $now,
            ]);
            return redirect('/user/regist')->with([
                'custom_error_messages' => ['登録から24時間以上経過しました。登録を完了する場合はメールを送信いたしますので「再送信する」をクリックしてください。'],
                'ticket' => $auth_key->ticket,
            ]);
        }

        $this->log('info', 'success to limit check', [
            'regist' => $auth_key->application_datetime,
            'limit' => $limit,
            'now' => $now,
        ]);

        //会員情報取得
        $user = Tr_users::getUserByMail($auth_key->mail)->get()->first();

        //会員情報更新
        DB::transaction(function () use ($user) {
            try {
                Tr_users::where('mail', $user->mail)
                        ->where('delete_flag', $user->delete_flag)
                        ->where('delete_date', $user->delete_date)->update([
                    'auth_flag' => 0,
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        //メールアドレスの認証鍵を物理削除
        $auth_key->delete();

        //メール送信
        $data = [
            'user_name' => $user->last_name .$user->first_name,
            'mail' => $user->mail
        ];

        $frntUtil = new FrntUtil();
        Mail::send('front.emails.user_regist', $data, function ($message) use ($data, $frntUtil) {
            $message->from($frntUtil->user_regist_mail_from, $frntUtil->user_regist_mail_from_name);
            $message->to($data['mail']);
            if (!empty($frntUtil->user_regist_mail_to_bcc)) {
                $message->bcc($frntUtil->user_regist_mail_to_bcc);
            }
            $message->subject(FrntUtil::USER_REGIST_MAIL_TITLE);
        });

        $id = $user->id;
        $mail = $user->mail;
    
        return view('front.user_complete' , compact('id','mail'));
    }

    /**
     * 新規会員登録画面表示
     * GET:/user/regist
     */
    public function index(Request $request){

        if(!empty($request->custom_error_messages)){
            redirect('/user/regist')->with('custom_error_messages',[$request->custom_error_messages]);
        }

        //登録から24時間以上経過したときの再送信
        if(!empty($request->ticket)){

            $auth_key = Tr_auth_keys::where('ticket', $request->ticket)
                                    ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                                    ->get()
                                    ->first();
            //会員情報取得
            $user = Tr_users::getUserByMail($auth_key->mail)->get()->first();

            $data_db = [
                'auth_key'  => $auth_key,
                'ticket'    => FrntUtil::createUUID(),
                'now'       => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            // トランザクション
            DB::transaction(function () use ($data_db) {
                try {
                    //アップデート
                    $data_db['auth_key']->application_datetime = $data_db['now'];
                    $data_db['auth_key']->ticket = $data_db['ticket'];
                    $data_db['auth_key']->save();
                } catch (Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });

            // メール送信
            $data_mail = [
                'user_name' => $user->last_name .$user->first_name,
                'mail' => $auth_key->mail,
                'limit' => FrntUtil::AUTH_KEY_LIMIT_HOUR,
                'url' => url('/user/regist/comp?ticket='.$data_db['ticket']),
            ];

            $frntUtil = new FrntUtil();
            Mail::send('front.emails.mail_auth', $data_mail, function ($message) use ($data_mail, $frntUtil) {
                $message->from($frntUtil->regist_mail_auth_mail_from, $frntUtil->regist_mail_auth_mail_from_name);
                $message->to($data_mail['mail']);
                $message->subject(FrntUtil::MAIL_TITLE_REGIST_MAIL_AUTH);
            });

            redirect('/user/regist')->with('resend_comp',['再送信が完了しました。']);
        }

        //SNS認証したとき
        if(!empty($request->token) && !empty($request->sns)){

            //twitter
            if($request->sns === 'twitter' && !empty($request->tokenSecret)){
                try {
                    $t_user = Socialite::driver('twitter')->userFromTokenAndSecret($request->token, $request->tokenSecret);
                } catch (\Exception $e) {
                    // 認証失敗
                    $this->log('error', 'failure to twitter auth', [$e->getMessage()]);
                    return redirect("/");
                }

                $email = $t_user->email;
                return view('front.user_input' , compact('email'));
            }

            //facebook
            //2019.4.17 Facebookプラットフォームポリシー
            //以下に該当する可能性があるためコメントアウト
            //ログイン項目
            //4.要求するデータおよび投稿アクセス許可は、優れたユーザーエクスペリエンスを提供するために、アプリにとって必要なものだけに限定してください。
            //5.書き込みアクセス許可は、それがアプリに必要な場合のみ要求してください(例えば、ユーザーがアプリにログインした直後に書き込みアクセス許可を要求しないでください)。
            //6.Facebookから受け取ったユーザーデータを独自のデータに変換すること(例えば、Facebookから受け取った利用者のデータをデータフィールドに自動入力し、そのデータの送信または保存をユーザーに要求すること)はできません。
            // if($request->sns === 'facebook'){
            //     try {
            //         $f_user = Socialite::driver('facebook')->fields(['name', 'last_name', 'first_name', 'email'])->userFromToken($request->token);
            //     } catch (\Exception $e) {
            //         // 認証失敗
            //         $this->log('error', 'failure to facebook auth', [$e->getMessage()]);
            //         return redirect("/");
            //     }
                
            //     if(isset($f_user->user['last_name'])){
            //         $last_name = $f_user->user['last_name'];
            //     }else{
            //         $last_name = '';
            //     }

            //     if(isset($f_user->user['first_name'])){
            //         $first_name = $f_user->user['first_name'];
            //     }else{
            //         $first_name = '';
            //     }

            //     $email = $f_user->user['email'];

            //     return view('front.user_input' , compact('last_name', 'first_name', 'email'));
            // }

            //github
            if($request->sns === 'github'){

                try {
                    $g_user = Socialite::driver('github')->userFromToken($request->token);
                } catch (\Exception $e) {
                    // 認証失敗
                    $this->log('error', 'failure to github auth', [$e->getMessage()]);
                    return redirect("/");
                }

                $email = $g_user->email;
                return view('front.user_input' , compact('email'));
            }
        }
        return view('front.user_input');
    }

    /**
     * 会員登録処理
     * POST:/user/regist
     */
    public function store(UserRegistrationRequest $request) {

        // prefix_saltを作成   
        $prefix_salt = FrntUtil::getPrefixSalt(20); 
        // 必須項目以外はnullを指定   
        // empty()で判別しているため、'0'は空文字扱い   
        $regist_data = [    
            'last_name'         => $request->last_name, 
            'first_name'        => $request->first_name,   
            'last_name_kana'    => $request->last_name_kana,   
            'first_name_kana'   => $request->first_name_kana,   
            'gender'            => $request->gender,
            'mail'              => $request->mail,
            'salt'              => $prefix_salt,
            'password'          => $prefix_salt .$request->password .FrntUtil::FIXED_SALT,
            'phone_num'         => $request->phone_num,
            'prefecture_id'     => $request->prefecture_id,
            'birth'             => date('Y-m-d', strtotime($request->birth)),
            'country'           => "日本",
            'magazine_flag'     => 1,
            'skillsheet_upload' => 0,
            'now'               => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $social_data = [
            'social_conection_type' => session()->pull('social_conection_type', null),
            'social_conection_id'   => session()->pull('social_conection_id', null),
            'now'                   => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        //SNS連携用のsessionがあれば削除
        if (session()->has(SsnUtil::SESSION_KEY_SOCIAL_TYPE)){
            session()->forget(SsnUtil::SESSION_KEY_SOCIAL_TYPE);
        }
        if (session()->has(SsnUtil::SESSION_KEY_SOCIAL_ID)){
            session()->forget(SsnUtil::SESSION_KEY_SOCIAL_ID);
        }

        $auth_data = [
            'auth_task' => MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT,
            'ticket'    => FrntUtil::createUUID(),
            'mail'      => $request->mail,
            'now'       => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        // 会員登録トランザクション 
        DB::transaction(function () use ($regist_data, $social_data) {  
            try {   
                $user = new Tr_users;
                $user->auth_flag         = 1;
                $user->last_name         = $regist_data['last_name'];
                $user->first_name        = $regist_data['first_name'];
                $user->last_name_kana    = $regist_data['last_name_kana'];
                $user->first_name_kana   = $regist_data['first_name_kana'];
                $user->sex               = $regist_data['gender'];
                $user->mail              = $regist_data['mail'];
                $user->salt              = $regist_data['salt'];
                $user->password          = md5($regist_data['password']);
                $user->tel               = $regist_data['phone_num'];
                $user->prefecture_id     = $regist_data['prefecture_id'];
                $user->birth_date        = $regist_data['birth'];
                $user->nationality       = $regist_data['country'];
                $user->magazine_flag     = $regist_data['magazine_flag'];
                $user->skillsheet_upload = $regist_data['skillsheet_upload'];
                $user->registration_date = $regist_data['now'];
                $user->last_login_date   = $regist_data['now'];
                $user->save();

                // SNS連携データが存在する場合
                if (!empty($social_data['social_conection_type']) && !empty($social_data['social_conection_id'])) {
                    $social_account                      = new Tr_user_social_accounts;
                    $social_account->user_id             = $user->id;
                    $social_account->social_account_id   = $social_data['social_conection_id'];
                    $social_account->social_account_type = $social_data['social_conection_type'];
                    $social_account->registration_date   = $social_data['now'];
                    $social_account->last_update_date    = $social_data['now'];
                    $social_account->save();
                }
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // メール認証トランザクション
        DB::transaction(function () use ($auth_data) {           
            try {
                if (empty($auth_data['auth_key'])) {
                    // インサート
                    $auth_data['auth_key'] = new Tr_auth_keys;
                    $auth_data['auth_key']->application_datetime = $auth_data['now'];
                    $auth_data['auth_key']->auth_task = $auth_data['auth_task'];
                } else {
                    // アップデート
                    $auth_data['auth_key']->application_datetime = $auth_data['now'];
                }
                $auth_data['auth_key']->mail = $auth_data['mail'];
                $auth_data['auth_key']->ticket = $auth_data['ticket'];
                $auth_data['auth_key']->save();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // メール送信
        $data_mail = [
            'user_name' => $request->last_name .$request->first_name,
            'mail'      => $request->mail,
            'limit'     => FrntUtil::AUTH_KEY_LIMIT_HOUR,
            'url'       => url('/user/regist/comp?ticket='.$auth_data['ticket']),
        ];

        $frntUtil = new FrntUtil();
        Mail::send('front.emails.mail_auth', $data_mail, function ($message) use ($data_mail, $frntUtil) {
            $message->from($frntUtil->regist_mail_auth_mail_from, $frntUtil->regist_mail_auth_mail_from_name);
            $message->to($data_mail['mail']);
            $message->subject(FrntUtil::MAIL_TITLE_REGIST_MAIL_AUTH);
        });

        return view('front.mail_auth');
    }

    /*
     * マイページ表示
     * GET:/user
     */
    public function showMyPage(){
        // ログインユーザ情報を取得
        $user = FrntUtil::getFirstLoginUser();
        return view('front.user_mypage' , compact('user'));
    }

    /*
     * メールアドレス変更画面表示
     * GET:/user/edit/email
     */
    public function showEmailEdit(){
        // ログインユーザを取得
        $user = FrntUtil::getFirstLoginUser();
        return view('front.edit_email' , compact('user'));
    }

    /*
     * メールアドレス変更処理
     * POST:/user/edit/email
     */
    public function updateEmailAuth(EditEmailRequest $request){

        // ログインユーザを取得
        $user = FrntUtil::getFirstLoginUser();

        // UUIDを生成
        $ticket = FrntUtil::createUUID();

        // 認証鍵を取得
        $auth_key = Tr_auth_keys::where('user_id', $user->id)
                                ->where('auth_task', MdlUtil::AUTH_TASK_CHANGE_MAIL_AUHT)
                                ->get()
                                ->first();

        $data_db = [
            'auth_key' => $auth_key,
            'auth_task' => MdlUtil::AUTH_TASK_CHANGE_MAIL_AUHT,
            'ticket' => $ticket,
            'mail' => $request->mail,
            'user_id' => $user->id,
            'now' => Carbon::now()->format('Y-m-d H:i:s'),

        ];

        // トランザクション
        $auth_key = DB::transaction(function () use ($data_db) {
            try {
                if (empty($data_db['auth_key'])) {
                    // インサート
                    $data_db['auth_key'] = new Tr_auth_keys;
                    $data_db['auth_key']->mail = $data_db['mail'];
                    $data_db['auth_key']->user_id = $data_db['user_id'];
                    $data_db['auth_key']->application_datetime = $data_db['now'];
                    $data_db['auth_key']->auth_task = $data_db['auth_task'];
                } else {
                    // アップデート
                    $data_db['auth_key']->application_datetime = $data_db['now'];
                }
                $data_db['auth_key']->ticket = $data_db['ticket'];
                $data_db['auth_key']->save();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
            return $data_db['auth_key'];
        });

        // メール送信
        $data_mail = [
            'auth_key' => $auth_key,
            'mail' => $request->mail,
            'limit' => FrntUtil::AUTH_KEY_LIMIT_HOUR,
        ];

        $frntUtil = new FrntUtil();
        Mail::send('front.emails.edit_mail_auth', $data_mail, function ($message) use ($data_mail, $frntUtil) {
            $message->from($frntUtil->change_mail_auth_mail_from, $frntUtil->change_mail_auth_mail_from_name);
            $message->to($data_mail['mail']);
            $message->subject(FrntUtil::MAIL_TITLE_CHANGE_MAIL_AUTH);
        });

        $this->log('info', 'send email change email auth', [
            'auth_key' => $auth_key,
        ]);

        return redirect('/user')->with('custom_info_messages', ['新しいメールアドレスにメールを送信しました。']);
    }

    /*
     * メールアドレス変更処理
     * GET:/user/edit/email/auth
     */
    public function updateEmail(Request $request){

        // id(user_id),ticketパラメータから認証鍵を取得
        $auth_key = Tr_auth_keys::where('user_id', $request->id)
                                ->where('ticket', $request->ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_CHANGE_MAIL_AUHT)
                                ->first();

        // 認証鍵が取得できなかった場合
        if (empty($auth_key)) {
            $this->log('error', 'entity not found(Tr_auth_keys)',[
                'user_id' => $request->id,
                'ticket' => $request->ticket,
                'auth_task' => MdlUtil::AUTH_TASK_CHANGE_MAIL_AUHT,
            ]);
            return redirect('/');
        }

        // 24時間以内のアクセスかチェック
        $limit = $auth_key->application_datetime->addDay();
        $now = Carbon::now();
        if (!$now->lte($limit)) {
            $this->log('error', 'limit over change mail auth', [
                'regist' => $auth_key->application_datetime,
                'limit' => $limit,
                'now' => $now,
            ]);
            return redirect('/');
        }

        $this->log('info', 'success to limit check', [
            'regist' => $auth_key->application_datetime,
            'limit' => $limit,
            'now' => $now,
        ]);

        // 更新対象ユーザを取得
        $user = Tr_users::where('id', $auth_key->user_id)
                        ->enable()
                        ->first();

        // 更新対象ユーザが取得できなかった場合
        if (empty($user)) {
            $this->log('error', 'entity not found(Tr_users)',[
                'user_id' => $auth_key->id,
            ]);
            return redirect('/');
        }

        // メールアドレスが一意であることを再チェック
        $check_user = Tr_users::where('mail', $auth_key->mail)
                              ->enable()
                              ->get();

        // 変更後メールアドレスが一意でない場合
        if (!$check_user->isEmpty()) {
            $this->log('error', 'duplicate email',[
                'mail' => $auth_key->mail,
            ]);
            return redirect('/');
        }

        $db_data = [
            'user' => $user,
            'auth_key' => $auth_key,
        ];

        // トランザクション
        $comp_user = DB::transaction(function () use ($db_data) {
            try {
                // ユーザーをアップデート
                $db_data['user']->mail = $db_data['auth_key']->mail;
                $db_data['user']->save();

                // 認証鍵をハードデリート
                $db_data['auth_key']->delete();

                return $db_data['user'];

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        $this->log('info', 'success to change email',[
            'user_id' => $user->id,
            'before_email' => $user->mail,
            'after_email' => $comp_user->mail,
        ]);

        // マイページへ遷移（ログイン中でなければトップ）
        return redirect('/user')->with('custom_info_messages', ['メールアドレス変更は正常に完了しました。']);
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
        $login_user = FrntUtil::getFirstLoginUser();

        if (empty($login_user)) {
            return redirect('/');
        }

        $db_data = [
            'user' => $login_user,
            'delete_date' => Carbon::today()->format('Y-m-d'),
        ];

        // トランザクション
        DB::transaction(function () use ($db_data) {
            try {
                // ユーザーをアップデート
                $db_data['user']->delete_flag = $db_data['user']->id;
                $db_data['user']->delete_date = $db_data['delete_date'];
                $db_data['user']->save();

                // SNS連携テーブルを削除
                Tr_user_social_accounts::where('user_id', $db_data['user']->id)->delete();

            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // ログアウト処理へリダイレクト
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

        // UUIDを生成
        $ticket = FrntUtil::createUUID();

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
            'mail' => $request->mail,
        ];

        $frntUtil = new FrntUtil();
        Mail::send('front.emails.user_reminder', $mail_data, function ($message) use ($mail_data, $frntUtil) {
            $message->from($frntUtil->user_reminder_mail_from, $frntUtil->user_reminder_mail_from_name);
            $message->to($mail_data['mail']);
            $message->subject(FrntUtil::USER_REMINDER_MAIL_TITLE);
        });

        return view('front.user_reminder_complete');
    }

    /*
     * パスワード再設定画面
     * GET:/user/recovery
     */
    public function showRecovery(Request $request){

        // 認証鍵を取得
        $auth_key = Tr_auth_keys::where('id', $request->id)
                                ->where('ticket', $request->ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_RECOVER_PASSWORD)
                                ->first();

        // 認証鍵が取得できなかった場合
        if (empty($auth_key)) {
            $this->log('error', 'entity not found(Tr_auth_keys)',[
                'auth_id' => $request->id,
                'ticket' =>  $request->ticket,
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        // 60分以内のアクセスかチェック
        $limit = $auth_key->application_datetime->addHour();
        $now = Carbon::now();
        if (!$now->lte($limit)) {
            $this->log('error', 'limit over change password auth', [
                'regist' => $auth_key->application_datetime,
                'limit' => $limit,
                'now' => $now,
            ]);
            return redirect('/');
        }

        $this->log('info', 'success to limit check', [
            'regist' => $auth_key->application_datetime,
            'limit' => $limit,
            'now' => $now,
        ]);

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

        // 認証鍵を取得
        $auth_key = Tr_auth_keys::where('id', $request->id)
                                ->where('ticket', $request->ticket)
                                ->where('auth_task', MdlUtil::AUTH_TASK_RECOVER_PASSWORD)
                                ->first();

        // 認証鍵が存在しない場合
        if (empty($auth_key)) {
            $this->log('error', 'entity not found(Tr_auth_keys)',[
                'auth_id' => $request->id,
                'ticket' =>  $request->ticket,
            ]);
            return redirect('/'); // TODO: あとで汎用エラー画面つくる
        }

        // 60分以内のアクセスかチェック
        $limit = $auth_key->application_datetime->addHour();
        $now = Carbon::now();
        if (!$now->lte($limit)) {
            $this->log('error', 'limit over change password auth', [
                'regist' => $auth_key->application_datetime,
                'limit' => $limit,
                'now' => $now,
            ]);
            return redirect('/');
        }

        $this->log('info', 'success to limit check', [
            'regist' => $auth_key->application_datetime,
            'limit' => $limit,
            'now' => $now,
        ]);

        // 認証鍵から対象のユーザを取得
        $user = Tr_users::where('id', $auth_key->user_id)
                        ->enable()
                        ->first();

        // 対象のユーザが存在しない場合
        if (empty($user)) {
            $this->log('error', 'entity not found(Tr_users)',[
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

        $this->log('info', 'success to password recovery',[
            'user_id' => $user->id,
        ]);

        return view('front.recover_password_complete');
    }

    /*
     * プロフィール変更画面表示
     * GET:/user/edit
     */
    public function showUserEdit(){
        // ログインユーザを取得
        $user = FrntUtil::getFirstLoginUser();
        $file_count = 0;
        if(!empty($user->skillsheet_1)){
            $file_count++;
        }
        if(!empty($user->skillsheet_2)){
            $file_count++;
        }
        if(!empty($user->skillsheet_3)){
            $file_count++;
        }
        return view('front.user_edit', compact('user','file_count'));
    }

    /*
     * プロフィール変更処理
     * POST:/user/edit
     */
    public function updateUser(UserEditRequest $request){
        // ログインユーザを取得
        $user = FrntUtil::getFirstLoginUser();
        //-------------------------------------------------------------------
        //スキルシートのチェック
        //-------------------------------------------------------------------
        //バリデーションエラーでアップロードを何度もやり直していることを考慮
        //登録ボタンを押すたびに新しい配列が作成されるため最新の配列のみチェックする

        //スキルシートアップのみ
        $skillsheet_upOnly = 'skillsheet_upOnly_'.$request->uploadCount;
        //スキルシート全部
        $skillsheet_all = 'skillsheet_all_'.$request->uploadCount;
        //希望の契約形態
        $contract_types = 'contract_types_'.$request->uploadCount;
        //拡張子
        $file_extension = array();
        //エラーメッセージ
        $custom_error_messages = array();
        //アップロードフラグ
        $skillsheet_upload_flag = 0;
        $skillsheet_updoad_1 = '';
        $skillsheet_updoad_2 = '';
        $skillsheet_updoad_3 = '';

        //「ドラッグ&ドロップ」または「ファイル選択」のとき
        if($request->file_type == 'user_edit_dd' || $request->file_type == 'user_edit_fe'){
            $fileCount = 0;
            //追加アップロードしたファイルをチェック
            foreach($request->$skillsheet_upOnly as $key => $file) {
                if(!empty($file)) {
                    // サイズチェック
                    if ($file->getClientSize() > FrntUtil::FILE_UPLOAD_RULE['maximumSize']) {
                        array_push($custom_error_messages, 'スキルシートが1MBを超えています。');
                    }
                    // 拡張子チェック
                    $original_name = collect(explode('.', $file->getClientOriginalName()));
                    if ($original_name->count() != 2
                        || !in_array($original_name->last(), FrntUtil::FILE_UPLOAD_RULE['allowedExtensions'])) {
                            array_push($custom_error_messages, 'スキルシートの拡張子が正しくありません。');
                    }
                    // mimeTypeチェック
                    $mime_type = shell_exec('file -b --mime '.escapeshellcmd($_FILES[$skillsheet_upOnly]['tmp_name'][$key]));
                    $mime_type = trim($mime_type);
                    $mime_type = collect(explode(';', $mime_type));

                    if ($mime_type->isEmpty() || (!$mime_type->isEmpty() && !in_array($mime_type->first(), FrntUtil::FILE_UPLOAD_RULE['allowedTypes']))) {
                            array_push($custom_error_messages, 'スキルシートのファイル形式が正しくありません。');
                    }
                    $file_extension[$key] = $original_name->last();
                    // $skillsheet_upload_flag = 1;
                }else{
                    $file_extension[$key] = '';
                    if(1 < $fileCount){
                        array_push($custom_error_messages, 'スキルシートがアップロードされていません。');
                    }
                    $fileCount++;
                }
            }     
        }

        if (!empty($custom_error_messages)) {
            $data_content = ['custom_error_messages' => $custom_error_messages];
            echo json_encode($data_content);
        }  
       
        //エラーがないとき
        if(empty($custom_error_messages)){

            //メールまたはチェックなしのとき
            if($request->file_type == 'user_edit_fma' || $request->file_type == ''){
                $file_extension[] = null;
            }

            //ファイルがあればフラグを1にする
            foreach($request->$skillsheet_all as $file){
                if(!empty($file)){
                    $skillsheet_upload_flag = 1;             
                }
            }

            //編集前の情報を確認
            if(!empty($user->skillsheet_1) && !empty($request->$skillsheet_all[0])){
                if($user->skillsheet_1 == $request->$skillsheet_all[0]){ 
                    $skillsheet_updoad_1 = $user->skillsheet_1;
                }
            }
            if(!empty($user->skillsheet_2) && !empty($request->$skillsheet_all[1])){
                if($user->skillsheet_2 == $request->$skillsheet_all[1]){ 
                    $skillsheet_updoad_2 = $user->skillsheet_2;
                }
            }
            if(!empty($user->skillsheet_3) && !empty($request->$skillsheet_all[2])){
                if($user->skillsheet_3 == $request->$skillsheet_all[2]){
                    $skillsheet_updoad_3 = $user->skillsheet_3;
                }
            }

            //必須項目以外は、入力されていない場合nullを指定（契約形態以外）
            //?:は内部的にはempty()と同義のため、'0'は空文字扱い
            $db_data = [
                    'user'                  => $user,
                    'last_name'             => $request->last_name,
                    'first_name'            => $request->first_name,
                    'last_name_kana'        => $request->last_name_kana,
                    'first_name_kana'       => $request->first_name_kana,
                    'gender'                => $request->gender,
                    'birth'                 => date('Y-m-d', strtotime($request->birth)),
                    'education'             => $request->education ?: null,
                    'country'               => $request->country ?: null,
                    'contract_types'        => $request->$contract_types,
                    'prefecture_id'         => $request->prefecture_id,
                    'station'               => $request->station ?: null,
                    'phone_num'             => $request->phone_num,
                    'magazine_flag'         => $request->magazine_flag,
                    'skillsheet_upload'     => $skillsheet_upload_flag,
                    'skillsheet_1'          => $skillsheet_updoad_1 ?: null,
                    'skillsheet_2'          => $skillsheet_updoad_2 ?: null,
                    'skillsheet_3'          => $skillsheet_updoad_3 ?: null,
                    'file_name'             => 'skillsheetEN',
                    'file_extension'        => $file_extension,
                    'file_type'             => $request->file_type,
                ];

            $skillsheet_list = $request->$skillsheet_upOnly;

            //トランザクション
            $db_return_data = DB::transaction(function () use ($db_data, $skillsheet_list, $user) {
                try {
                    // ユーザーテーブルにインサート
                    $db_data['user']->first_name        = $db_data['first_name'];
                    $db_data['user']->last_name         = $db_data['last_name'];
                    $db_data['user']->first_name_kana   = $db_data['first_name_kana'];
                    $db_data['user']->last_name_kana    = $db_data['last_name_kana'];
                    $db_data['user']->sex               = $db_data['gender'];
                    $db_data['user']->birth_date        = $db_data['birth'];
                    $db_data['user']->education_level   = $db_data['education'];
                    $db_data['user']->nationality       = $db_data['country'];
                    $db_data['user']->prefecture_id     = $db_data['prefecture_id'];
                    $db_data['user']->station           = $db_data['station'];
                    $db_data['user']->tel               = $db_data['phone_num'];
                    $db_data['user']->magazine_flag     = $db_data['magazine_flag'];
                    $db_data['user']->skillsheet_upload = $db_data['skillsheet_upload'];
                    $db_data['user']->skillsheet_1      = $db_data['skillsheet_1'];
                    $db_data['user']->skillsheet_2      = $db_data['skillsheet_2'];
                    $db_data['user']->skillsheet_3      = $db_data['skillsheet_3'];
                    $db_data['user']->save();

                    // ユーザ契約形態中間テーブルをデリートインサート
                    Tr_link_users_contract_types::where('user_id', $db_data['user']->id)->delete();
                    foreach ((array)$db_data['contract_types'] as $contract_type) {
                        Tr_link_users_contract_types::create([
                            'user_id' => $db_data['user']->id,
                            'contract_type_id' => $contract_type,
                        ]);
                    }

                    //ドラッグ&ドロップまたはファイル洗濯、
                    //かつアップロードフラグ1かつスキルシートアップのみに値があるとき
                    if(($db_data['file_type'] == 'user_edit_dd' || $db_data['file_type'] == 'user_edit_fe') && ($db_data['user']->skillsheet_upload && !empty($skillsheet_list))){
        
                        //採番されたIDでファイル名を生成、アップデート
                        $name = $db_data['file_name'].$user->id;
                        foreach ($skillsheet_list as $key => $file) {
                            if(!empty($file)) {
                                $key_plus = $key + 1;
                                $skillsheet_key = 'skillsheet_'.$key_plus;
                                $db_data['user']->$skillsheet_key = $name.'_no'.$key_plus.'.'.$db_data['file_extension'][$key];
           
                                $file_name[$key] = $user->$skillsheet_key;
                            }else{
                                $file_name[$key] = null;
                            }
                        }
                        $db_data['user']->save();
                    }else{
                        $file_name = null;
                    }       
                    return ['file_name' => $file_name];
                } catch (Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });

            // ファイルをローカルに保存
            if($skillsheet_upload_flag && !empty($skillsheet_list)){
                foreach ($skillsheet_list as $key => $file) {
                    if(!empty($file)) {
                        $file->move(storage_path('app'), $db_return_data['file_name'][$key]);
                    }
                }
            }
            
            $data_content = ['url' => '/user'];
            //エンコードして返却
            echo json_encode($data_content); 
        }
    }

    /*
     * エントリー済み案件一覧表示
     * GET:/user/entry
     */
    public function showEntryList() {

        // ログインユーザを取得
        $login_user = FrntUtil::getFirstLoginUser();

        // 有効なエントリー済み案件を取得、 受付日の降順にソート
        $entry_list = Tr_item_entries::where('user_id', $login_user->id)
                                     ->where('delete_flag', 0)
                                     ->where('delete_date', null)
                                     ->orderBy('entry_date', 'desc')
                                     ->get();

        /* developで動かすとここでエントリーがすべて消える
        $entry_list = $login_user->entries->where('delete_flag', 0)
                                          ->where('delete_date', null)
                                          ->sortByDesc('entry_date');
        */

        return view('front.user_entry', compact('entry_list'));
    }

    /*
     * スキルシート個別ダウンロード
     * GET:/user/skillsheet/download/oneByOne
     */
    public function downloadOneByOne(Request $request){
        // ログインユーザを取得
        $login_user = FrntUtil::getFirstLoginUser();

        // idパラメータから、該当のエントリー情報を取得
        $user = Tr_users::where('id', $request->id ?: null)
                                ->enable()
                                ->first();

        // ユーザー情報が存在しない場合
        if (empty($user)) {
            $this->log('error', 'entity not found(Tr_users)',[
                'id' => $request->id,
            ]);
            abort(404, '指定されたユーザー情報は存在しません。');
        }

        // ログインユーザのエントリー情報であることをチェック
        if ($login_user->id != $user->id) {
            $this->log('error', 'illegal operation',[
                'login_user_id' => $login_user->id,
                'user_id' => $user->id,
            ]);
            abort(400);
        }

        // エントリーシートの存在チェック
        $skillsheetCheck = false;
        $targetfile = '';
        switch ($request->skillsheet) {
            case 1:
                if(Storage::disk()->exists($user->skillsheet_1)){
                    $skillsheetCheck = true;
                }
                $targetfile = $user->skillsheet_1;
                break;
            case 2:
                if(Storage::disk()->exists($user->skillsheet_2)){
                    $skillsheetCheck = true;
                }
                $targetfile = $user->skillsheet_2;
                break;
            case 3:
                if(Storage::disk()->exists($user->skillsheet_3)){
                    $skillsheetCheck = true;
                }
                $targetfile = $user->skillsheet_3;
                break;
        }

        if(!$user->skillsheet_upload || !$skillsheetCheck){
            $this->log('error', 'skillsheet not found',[
                'skillsheet_upload' => $user->skillsheet_upload,
                'skillsheet_1'      => $user->skillsheet_1,
                'skillsheet_2'      => $user->skillsheet_2,
                'skillsheet_3'      => $user->skillsheet_3,
            ]);
            abort(404, 'スキルシートが見つかりません');
        }

        $zip = new ZipArchive();
        $path = storage_path('app/');

        //処理対象のファイルの存在チェックを行い、存在するもののみのリストを作成
        $existsfiles = array();
        if (file_exists($path.$targetfile) && is_file($path.$targetfile)) {
            $existsfiles[] = $targetfile;
        }

        // 存在するもののみのリストにしたがってzipを作成
        if (count($existsfiles) > 0) {
            $zip->open(storage_path('app/').'skillsheet.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
            foreach($existsfiles as $targetfile){
                $zip->addFile($path.$targetfile, $targetfile);
            }
            $zip->close();
        } else {
            abort(404, 'ZIPファイルを作ることができませんでした');
        }

        // ダウンロード
        return response()->download(storage_path('app/').'skillsheet.zip');
    }

    /*
     * スキルシートダウンロード
     * GET:/user/entry/download
     */
    public function donwload(Request $request) {

        // ログインユーザを取得
        $login_user = FrntUtil::getFirstLoginUser();

        // idパラメータから、該当のエントリー情報を取得
        $entry = Tr_item_entries::where('id', $request->id ?: null)
                                ->enable()
                                ->first();

        // エントリー情報が存在しない場合
        if (empty($entry)) {
            $this->log('error', 'entity not found(Tr_item_entries)',[
                'id' => $request->id,
            ]);
            abort(404, '指定されたエントリー情報は存在しません。');
        }

        // ログインユーザのエントリー情報であることをチェック
        if ($login_user->id != $entry->user_id) {
            $this->log('error', 'illegal operation',[
                'login_user_id' => $login_user->id,
                'entry_user_id' => $entry->user_id,
            ]);
            abort(400);
        }

        // エントリーシートの存在チェック
        $skillsheetCheck = false;
        if(Storage::disk()->exists($entry->skillsheet_1)){
            $skillsheetCheck = true;
        }
        if(Storage::disk()->exists($entry->skillsheet_2)){
            $skillsheetCheck = true;
        }
        if(Storage::disk()->exists($entry->skillsheet_3)){
            $skillsheetCheck = true;
        }
        if(!$entry->skillsheet_upload || !$skillsheetCheck){
            $this->log('error', 'skillsheet not found',[
                'skillsheet_upload' => $entry->skillsheet_upload,
                'skillsheet_1'      => $entry->skillsheet_1,
                'skillsheet_2'      => $entry->skillsheet_2,
                'skillsheet_3'      => $entry->skillsheet_3,
            ]);
            abort(404, 'エントリーシートが見つかりません');
        }

        $zip = new ZipArchive();
        $path = storage_path('app/');
        $targetfiles = array(
                    $entry->skillsheet_1, 
                    $entry->skillsheet_2, 
                    $entry->skillsheet_3
                );

        //処理対象のファイルの存在チェックを行い、存在するもののみのリストを作成
        $existsfiles = array();
        foreach($targetfiles as $targetfile){
            if (file_exists($path.$targetfile) && is_file($path.$targetfile)) {
                $existsfiles[] = $targetfile;
            }
        }

        // 存在するもののみのリストにしたがってzipを作成
        if (count($existsfiles) > 0) {
            $zip->open(storage_path('app/').'skillsheet.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
            foreach($existsfiles as $targetfile){
                $zip->addFile($path.$targetfile, $targetfile);
            }
            $zip->close();
        } else {
            abort(404, 'ZIPファイルを作ることができませんでした');
        }

        // ダウンロード
        return response()->download(storage_path('app/').'skillsheet.zip');
    }

    /*
     * スキルシートダウンロード
     * GET:/user/skillsheet/download
     */
    public function downloadSkillSheet(Request $request) {

        // ログインユーザを取得
        $login_user = FrntUtil::getFirstLoginUser();

        // idパラメータから、該当のエントリー情報を取得
        $user = Tr_users::where('id', $request->id ?: null)
                                ->enable()
                                ->first();

        // ユーザー情報が存在しない場合
        if (empty($user)) {
            $this->log('error', 'entity not found(Tr_users)',[
                'id' => $request->id,
            ]);
            abort(404, '指定されたユーザー情報は存在しません。');
        }

        // ログインユーザのエントリー情報であることをチェック
        if ($login_user->id != $user->id) {
            $this->log('error', 'illegal operation',[
                'login_user_id' => $login_user->id,
                'user_id' => $user->id,
            ]);
            abort(400);
        }

        // エントリーシートの存在チェック
        $skillsheetCheck = false;
        if(Storage::disk()->exists($user->skillsheet_1)){
            $skillsheetCheck = true;
        }
        if(Storage::disk()->exists($user->skillsheet_2)){
            $skillsheetCheck = true;
        }
        if(Storage::disk()->exists($user->skillsheet_3)){
            $skillsheetCheck = true;
        }

        if(!$user->skillsheet_upload || !$skillsheetCheck){
            $this->log('error', 'skillsheet not found',[
                'skillsheet_upload' => $user->skillsheet_upload,
                'skillsheet_1'      => $user->skillsheet_1,
                'skillsheet_2'      => $user->skillsheet_2,
                'skillsheet_3'      => $user->skillsheet_3,
            ]);
            abort(404, 'スキルシートが見つかりません');
        }

        $zip = new ZipArchive();
        $path = storage_path('app/');
        $targetfiles = array(
                    $user->skillsheet_1, 
                    $user->skillsheet_2, 
                    $user->skillsheet_3
                );

        //処理対象のファイルの存在チェックを行い、存在するもののみのリストを作成
        $existsfiles = array();
        foreach($targetfiles as $targetfile){
            if (file_exists($path.$targetfile) && is_file($path.$targetfile)) {
                $existsfiles[] = $targetfile;
            }
        }

        // 存在するもののみのリストにしたがってzipを作成
        if (count($existsfiles) > 0) {
            $zip->open(storage_path('app/').'skillsheet.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
            foreach($existsfiles as $targetfile){
                $zip->addFile($path.$targetfile, $targetfile);
            }
            $zip->close();
        } else {
            abort(404, 'ZIPファイルを作ることができませんでした');
        }

        // ダウンロード
        return response()->download(storage_path('app/').'skillsheet.zip');
    }
}