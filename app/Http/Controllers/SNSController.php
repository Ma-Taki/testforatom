<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Tr_user_twitter_accounts;
use App\Models\Tr_user_facebook_accounts;
use App\Models\Tr_user_social_accounts;
use App\Models\Tr_users;
use App\Models\Tr_user_github_accounts;
use App\Models\Tr_auth_keys;
use App\Libraries\ModelUtility as MdlUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\CookieUtility as CkieUtil;
use Socialite;
use Log;
use Carbon\Carbon;

class SNSController extends Controller
{
    /**
     * Twitter認証
     * GET:/login/sns/twitter
     *
     */
    public function getTwitterAuth(Request $request) {
        \Session::flash('OAuth_func', $request->func ?: '');
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Twitter認証コールバック
     * GET:/login/sns/twitter/callback
     */
    public function getTwitterAuthCallback() {

        try {
            $t_user = Socialite::driver('twitter')->user();

        } catch (\Exception $e) {
            // 認証失敗
            $this->log('error', 'failure to twitter auth', [$e->getMessage()]);
            return redirect("/");
        }

        $oauth_func = \Session::get('OAuth_func');

        // 認証成功
        $this->log('info', 'success to twitter auth', [
            'oauth_func' => $oauth_func,
            'twitter_id' => $t_user->id,
            'accessToken' => $t_user->token,
        ]);

        // twitterアカウント情報を取得
        $twitter_account = Tr_user_twitter_accounts::getAccountByTwitterId($t_user->id)->get()->first();

        // twitterアカウント情報がテーブルに存在しない場合、インサートする
        if (empty($twitter_account)) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $twitter_account = new Tr_user_twitter_accounts;
            $twitter_account->twitter_id = $t_user->id;
            $twitter_account->access_token_key = $t_user->token;
            $twitter_account->access_token_secret = $t_user->tokenSecret;
            $twitter_account->registration_date = $now;
            $twitter_account->last_update_date = $now;
            $twitter_account->save();

        } else {
            // 存在した場合アップデート
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $twitter_account->access_token_key = $t_user->token;
            $twitter_account->access_token_secret = $t_user->tokenSecret;
            $twitter_account->last_update_date = $now;
            $twitter_account->save();
        }

        // ログイン機能
        if(!empty($oauth_func) && $oauth_func == 'login') {

            // twitterアカウントに紐付いたユーザを取得
            $user = Tr_users::getUserBySnsAccount([
                'name' => 'twitter',
                'id' => $t_user->id,
                'type' => MdlUtil::SOCIAL_TYPE_TWITTER,
                ])->get()->first();

            // 紐付いたユーザが存在する場合
            if (!empty($user)) {

                //　ログイン成功
                $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
                CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

            // 紐付いたユーザが存在しない場合
            } else {

                // ログインユーザを取得
                $login_user = Tr_users::getLoginUser()->get()->first();

                // ログイン中の場合
                if(!empty($login_user)) {

                    // ユーザとソーシャルアカウントの紐付けテーブルを取得
                    $social_account = Tr_user_social_accounts::where('user_id', $login_user->id)
                                                             ->where('social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                                                             ->get()
                                                             ->first();

                    // 既に別のアカウントが紐付いている場合
                    if (!empty($social_account)) {
                        // 今回のアカウントでアップデート
                        $social_account->social_account_id = $twitter_account->id;
                        $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account->save();

                    } else {
                        // 今回のアカウントをインサート
                        $now = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account = new Tr_user_social_accounts;
                        $social_account->user_id = $login_user->id;
                        $social_account->social_account_id = $twitter_account->id;
                        $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_TWITTER;
                        $social_account->registration_date = $now;
                        $social_account->last_update_date = $now;
                        $social_account->save();
                    }
                // 未ログイン
                } else {
                    // メールアドレスからユーザを取得
                    $t_mail_user = Tr_users::getUserByMail($t_user->email)
                                           ->get()
                                           ->first();
                    // 会員登録済み
                    if (!empty($t_mail_user)) {
                        // ユーザとソーシャルアカウントの紐付けテーブルを取得
                        $social_account = Tr_user_social_accounts::where('user_id', $t_mail_user->id)
                                                                 ->where('social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                                                                 ->get()
                                                                 ->first();

                        // 既に別のアカウントが紐付いている場合
                        if (!empty($social_account)) {
                            // 今回のアカウントでアップデート
                            $social_account->social_account_id = $twitter_account->id;
                            $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account->save();

                        } else {
                            // 今回のアカウントをインサート
                            $now = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account = new Tr_user_social_accounts;
                            $social_account->user_id = $t_mail_user->id;
                            $social_account->social_account_id = $twitter_account->id;
                            $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_TWITTER;
                            $social_account->registration_date = $now;
                            $social_account->last_update_date = $now;
                            $social_account->save();
                        }

                        //　ログイン成功
                        $t_mail_user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                        $t_mail_user->save();
                        CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $t_mail_user->id);

                    } else {
                        return redirect('/login')->with('custom_error_messages', [
                            'Twitterアカウントと同じメールアドレスを持つユーザが見つかりませんでした。
                            <a class="hover-thin" href="/login/sns/twitter?func=regist">こちら</a>から会員登録を行ってください。<br>
                            既に会員の方は、マイページよりTwitterアカウントの認証を行ってください。'
                        ]);
                    }
                }
            }
            // ログイン成功時はマイページに遷移
            return redirect('/user');

        // 会員登録機能
        } else if(!empty($oauth_func) && $oauth_func == 'regist') {

            // アカウント連携テーブルを取得
            $local_t_user_account = Tr_user_social_accounts::join('user_twitter_accounts', 'user_social_accounts.social_account_id', '=', 'user_twitter_accounts.id')
                                                           ->where('user_twitter_accounts.twitter_id', '=', $t_user->id)
                                                           ->where('user_social_accounts.social_account_type', '=', MdlUtil::SOCIAL_TYPE_TWITTER)
                                                           ->get();

            // アカウント連携テーブルに、既に該当のTwitterアカウントが存在した場合
            if (!$local_t_user_account->isEmpty()) {
                $this->log('error', 'twitter account already exist', [
                    'oauth_func' => $oauth_func,
                    'twitter_id' => $t_user->id,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    'Twitterアカウントは既に使用されています。'
                ]);
            }

            //　Twitter API からメールアドレスを取得できた場合
            if (!empty($t_user->email)) {

                // メールアドレスからユーザを取得
                $t_mail_user = Tr_users::getUserByMail($t_user->email)->get();

                if (!$t_mail_user->isEmpty()) {
                    $this->log('error', 'twitter email already exist', [
                        'oauth_func' => $oauth_func,
                        'twitter_id' => $t_user->id,
                        'email' => $t_user->email,
                    ]);
                    return redirect('/user/regist/auth')->with('custom_error_messages', [
                        '使用しているメールアドレスは既に登録されています。'
                    ]);
                }

                // メールアドレス認証フローを実行
                $auth_key = Tr_auth_keys::where('mail', $t_user->email)
                                        ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                                        ->first();

                if (empty($auth_key)) {
                    // 認証鍵テーブルにインサート
                    $auth_key = new Tr_auth_keys;
                    $auth_key->mail = $t_user->email;
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                    $auth_key->auth_task = MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT;

                } else {
                    // 認証鍵テーブルにアップデート
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                }
                $auth_key->ticket = FrntUtil::createUUID();
                $auth_key->save();


                $this->log('info', 'success to email auth', [
                    'oauth_func' => $oauth_func,
                    'twitter_id' => $t_user->id,
                    'email' => $t_user->email,
                ]);

                // 会員登録のユーザ情報入力画面に遷移
                return redirect('/user/regist?ticket='.$auth_key->ticket);

            // Twitter API からメールアドレスを取得できなかった場合
            } else {
                $this->log('info', 'twitter email not found', [
                    'oauth_func' => $oauth_func,
                    'twitter_id' => $t_user->id,
                    'email' => $t_user->email,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    '有効なTwitterアカウントが見つかりませんでした。'
                ]);
            }

        // マイぺージからの認証
        } else if(!empty($oauth_func) && $oauth_func == 'auth') {

            // ログインユーザ取得
            $login_user = FrntUtil::getFirstLoginUser();

            // ミドルウェアで検証してないので、ログイン中であることをここでチェック
            if (empty($login_user)) {
                return redirect('/');
            }

            // アカウントが別のユーザに紐付いている場合
            $other_account = Tr_user_social_accounts::where('user_id', '!=', $login_user->id)
                                                    ->where('social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                                                    ->where('social_account_id', $twitter_account->id)
                                                    ->get();
            if (!$other_account->isEmpty()) {
                return redirect('/user')->with('custom_error_messages', [
                    'ご利用のTwitterアカウントは既に使用されています。'
                ]);
            }

            // ユーザとソーシャルアカウントの紐付けテーブルを取得
            $social_account = Tr_user_social_accounts::getTwitterAccount($login_user->id)->first();


            // 既に別のアカウントが紐付いている場合
            if (!empty($social_account)) {
                // 今回のアカウントでアップデート
                $social_account->social_account_id = $twitter_account->id;
                $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                $social_account->save();

            } else {
                // 今回のアカウントをインサート
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $social_account = new Tr_user_social_accounts;
                $social_account->user_id = $login_user->id;
                $social_account->social_account_id = $twitter_account->id;
                $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_TWITTER;
                $social_account->registration_date = $now;
                $social_account->last_update_date = $now;
                $social_account->save();
            }
            return redirect('/user');
        }

        return redirect('/');
    }

    /**
     * Facebook認証
     * GET:/login/sns/facebook
     *
     */
    public function getFacebookAuth(Request $request) {
        \Session::flash('OAuth_func', $request->func ?: '');
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Facebook認証コールバック
     * GET:/login/sns/facebook/callback
     */
    public function getFacebookAuthCallback() {

        try {
            $f_user = Socialite::driver('facebook')->user();

        } catch (\Exception $e) {
            // 認証失敗
            $this->log('error', 'failure to facebook auth', [$e->getMessage()]);
            return redirect("/");
        }

        $oauth_func = \Session::get('OAuth_func');

        // 認証成功
        $this->log('info', 'success to facebook auth', [
            'oauth_func' => $oauth_func,
            'facebook_id' => $f_user->id,
            'accessToken' => $f_user->token,
        ]);

        // facebookアカウント情報を取得
        $facebook_account = Tr_user_facebook_accounts::getAccountByFacebookId($f_user->id)
                                                     ->get()
                                                     ->first();

        // アカウント情報がテーブルに存在しない場合、インサートする
        if (empty($facebook_account)) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $facebook_account = new Tr_user_facebook_accounts;
            $facebook_account->facebook_id = $f_user->id;
            $facebook_account->access_token = $f_user->token;
            $facebook_account->registration_date = $now;
            $facebook_account->last_update_date = $now;
            $facebook_account->save();
        } else {
            // 存在した場合アップデート
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $facebook_account->access_token = $f_user->token;
            $facebook_account->last_update_date = $now;
            $facebook_account->save();
        }

        // ログイン機能
        if(!empty($oauth_func) && $oauth_func == 'login') {

            // facebookアカウントに紐付いたユーザを取得
            $user = Tr_users::getUserBySnsAccount([
                'name' => 'facebook',
                'id' => $f_user->id,
                'type' => MdlUtil::SOCIAL_TYPE_FACEBOOK,
                ])->get()->first();

            // 紐付いたユーザが存在する場合
            if (!empty($user)) {

                //　ログイン成功
                $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
                CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

            // 紐付いたユーザが存在しない場合
            } else {

                // ログインユーザを取得
                $login_user = FrntUtil::getFirstLoginUser();

                // ログイン中の場合
                if(!empty($login_user)) {

                    // ユーザとソーシャルアカウントの紐付けテーブルを取得
                    $social_account = Tr_user_social_accounts::where('user_id', $login_user->id)
                                                             ->where('social_account_type', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                                                             ->get()
                                                             ->first();

                    // 既に別のアカウントが紐付いている場合
                    if (!empty($social_account)) {
                        // 今回のアカウントでアップデート
                        $social_account->social_account_id = $facebook_account->id;
                        $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account->save();

                    } else {
                        // 今回のアカウントをインサート
                        $now = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account = new Tr_user_social_accounts;
                        $social_account->user_id = $login_user->id;
                        $social_account->social_account_id = $facebook_account->id;
                        $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_FACEBOOK;
                        $social_account->registration_date = $now;
                        $social_account->last_update_date = $now;
                        $social_account->save();
                    }
                // 未ログイン
                } else {
                    // メールアドレスからユーザを取得
                    $f_mail_user = Tr_users::getUserByMail($f_user->email)
                                           ->get()
                                           ->first();
                    // 会員登録済み
                    if (!empty($f_mail_user)) {
                        // ユーザとソーシャルアカウントの紐付けテーブルを取得
                        $social_account = Tr_user_social_accounts::where('user_id', $f_mail_user->id)
                                                                 ->where('social_account_type', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                                                                 ->get()
                                                                 ->first();

                        // 既に別のアカウントが紐付いている場合
                        if (!empty($social_account)) {
                            // 今回のアカウントでアップデート
                            $social_account->social_account_id = $facebook_account->id;
                            $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account->save();

                        } else {
                            // 今回のアカウントをインサート
                            $now = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account = new Tr_user_social_accounts;
                            $social_account->user_id = $f_mail_user->id;
                            $social_account->social_account_id = $facebook_account->id;
                            $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_FACEBOOK;
                            $social_account->registration_date = $now;
                            $social_account->last_update_date = $now;
                            $social_account->save();
                        }

                        //　ログイン成功
                        $f_mail_user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                        $f_mail_user->save();
                        CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $f_mail_user->id);

                    } else {
                        return redirect('/login')->with('custom_error_messages', [
                            'Facebookアカウントと同じメールアドレスを持つユーザが見つかりませんでした。
                            <a class="hover-thin" href="/login/sns/facebook?func=regist">こちら</a>から会員登録を行ってください。<br>
                            既に会員の方は、マイページよりFacebookアカウントの認証を行ってください。'
                        ]);
                    }
                }
            }
            // ログイン成功時はマイページに遷移
            return redirect('/user');

        // 会員登録機能
        } else if(!empty($oauth_func) && $oauth_func == 'regist') {

            // アカウント連携テーブルを取得
            $local_f_user_account = Tr_user_social_accounts::join('user_facebook_accounts', 'user_social_accounts.social_account_id', '=', 'user_facebook_accounts.id')
                                                           ->where('user_facebook_accounts.facebook_id', '=', $f_user->id)
                                                           ->where('user_social_accounts.social_account_type', '=', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                                                           ->get();

            // アカウント連携テーブルに、既に該当のFacebookアカウントが存在した場合
            if (!$local_f_user_account->isEmpty()) {
                $this->log('error', 'facebook account already exist', [
                    'oauth_func' => $oauth_func,
                    'facebook_id' => $f_user->id,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    'Facebookアカウントは既に使用されています。'
                ]);
            }

            //　Facebook API からメールアドレスを取得できた場合
            if (!empty($f_user->email)) {

                // メールアドレスからユーザを取得
                $f_mail_user = Tr_users::getUserByMail($f_user->email)->get();

                if (!$f_mail_user->isEmpty()) {
                    $this->log('error', 'facebook email already exist', [
                        'oauth_func' => $oauth_func,
                        'facebook_id' => $f_user->id,
                        'email' => $f_user->email,
                    ]);
                    return redirect('/user/regist/auth')->with('custom_error_messages', [
                        '使用しているメールアドレスは既に登録されています。'
                    ]);
                }

                // メールアドレス認証フローを実行
                $auth_key = Tr_auth_keys::where('mail', $f_user->email)
                                        ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                                        ->first();

                if (empty($auth_key)) {
                    // 認証鍵テーブルにインサート
                    $auth_key = new Tr_auth_keys;
                    $auth_key->mail = $f_user->email;
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                    $auth_key->auth_task = MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT;

                } else {
                    // 認証鍵テーブルにアップデート
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                }
                $auth_key->ticket = FrntUtil::createUUID();
                $auth_key->save();

                $this->log('info', 'success to email auth', [
                    'oauth_func' => $oauth_func,
                    'facebook_id' => $f_user->id,
                    'email' => $f_user->email,
                ]);

                // 会員登録のユーザ情報入力画面に遷移
                return redirect('/user/regist?ticket='.$auth_key->ticket);

            // Twitter API からメールアドレスを取得できなかった場合
            } else {
                $this->log('info', 'facebook email not found', [
                    'oauth_func' => $oauth_func,
                    'twitter_id' => $f_user->id,
                    'email' => $f_user->email,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    '有効なFacebookアカウントが見つかりませんでした。'
                ]);
            }

        // マイぺージからの認証
        } else if(!empty($oauth_func) && $oauth_func == 'auth') {

            // ログインユーザ取得
            $login_user = FrntUtil::getFirstLoginUser();

            // ミドルウェアで検証してないので、ログイン中であることをここでチェック
            if (empty($login_user)) {
                return redirect('/');
            }

            // アカウントが別のユーザに紐付いている場合
            $other_account = Tr_user_social_accounts::where('user_id', '!=', $login_user->id)
                                                    ->where('social_account_type', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                                                    ->where('social_account_id', $facebook_account->id)
                                                    ->get();
            if (!$other_account->isEmpty()) {
                return redirect('/user')->with('custom_error_messages', [
                    'ご利用のFacebookアカウントは既に使用されています。'
                ]);
            }

            // ユーザとソーシャルアカウントの紐付けテーブルを取得
            $social_account = Tr_user_social_accounts::getFacebookAccount($login_user->id)->first();


            // 既に別のアカウントが紐付いている場合
            if (!empty($social_account)) {
                // 今回のアカウントでアップデート
                $social_account->social_account_id = $facebook_account->id;
                $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                $social_account->save();

            } else {
                // 今回のアカウントをインサート
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $social_account = new Tr_user_social_accounts;
                $social_account->user_id = $login_user->id;
                $social_account->social_account_id = $facebook_account->id;
                $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_FACEBOOK;
                $social_account->registration_date = $now;
                $social_account->last_update_date = $now;
                $social_account->save();
            }
            return redirect('/user');
        }
        return redirect('/');
    }

    /**
     * Github認証
     * GET:/login/sns/github
     */
    public function getGithubAuth(Request $request) {
        \Session::flash('OAuth_func', $request->func ?: '');
        return Socialite::driver('github')->redirect();
    }

    /**
     * Github認証コールバック
     * GET:/login/sns/github/callback
     */
    public function getGithubAuthCallback() {

        try {
            $g_user = Socialite::driver('github')->user();

        } catch (\Exception $e) {
            // 認証失敗
            $this->log('error', 'failure to github auth', [$e->getMessage()]);
            return redirect("/");
        }

        $oauth_func = \Session::get('OAuth_func');

        // 認証成功
        $this->log('info', 'success to github auth', [
            'oauth_func' => $oauth_func,
            'github_id' => $g_user->id,
            'accessToken' => $g_user->token,
        ]);

        // githubアカウント情報を取得
        $github_account = Tr_user_github_accounts::getAccountByGithubId($g_user->id)->get()->first();

        // githubアカウント情報がテーブルに存在しない場合、インサートする
        if (empty($github_account)) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $github_account = new Tr_user_github_accounts;
            $github_account->github_id = $g_user->id;
            $github_account->access_token = $g_user->token;
            $github_account->registration_date = $now;
            $github_account->last_update_date = $now;
            $github_account->save();

        } else {
            // 存在した場合アップデート
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $github_account->access_token = $g_user->token;
            $github_account->last_update_date = $now;
            $github_account->save();
        }

        // ログイン機能
        if(!empty($oauth_func) && $oauth_func == 'login') {

            // githubアカウントに紐付いたユーザを取得
            $user = Tr_users::getUserBySnsAccount([
                'name' => 'github',
                'id' => $g_user->id,
                'type' => MdlUtil::SOCIAL_TYPE_GITHUB,
                ])->get()->first();

            // 紐付いたユーザが存在する場合
            if (!empty($user)) {

                //　ログイン成功
                $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
                CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

            // 紐付いたユーザが存在しない場合
            } else {

                // ログインユーザを取得
                $login_user = FrntUtil::getFirstLoginUser();

                // ログイン中の場合
                if(!empty($login_user)) {

                    // ユーザとソーシャルアカウントの紐付けテーブルを取得
                    $social_account = Tr_user_social_accounts::where('user_id', $login_user->id)
                                                             ->where('social_account_type', MdlUtil::SOCIAL_TYPE_GITHUB)
                                                             ->get()
                                                             ->first();

                    // 既に別のアカウントが紐付いている場合
                    if (!empty($social_account)) {
                        // 今回のアカウントでアップデート
                        $social_account->social_account_id = $github_account->id;
                        $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account->save();

                    } else {
                        // 今回のアカウントをインサート
                        $now = Carbon::now()->format('Y-m-d H:i:s');
                        $social_account = new Tr_user_social_accounts;
                        $social_account->user_id = $login_user->id;
                        $social_account->social_account_id = $github_account->id;
                        $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_GITHUB;
                        $social_account->registration_date = $now;
                        $social_account->last_update_date = $now;
                        $social_account->save();
                    }
                // 未ログイン
                } else {
                    // メールアドレスからユーザを取得
                    $g_mail_user = Tr_users::getUserByMail($g_user->email)
                                           ->get()
                                           ->first();
                    // 会員登録済み
                    if (!empty($g_mail_user)) {
                        // ユーザとソーシャルアカウントの紐付けテーブルを取得
                        $social_account = Tr_user_social_accounts::where('user_id', $g_mail_user->id)
                                                                 ->where('social_account_type', MdlUtil::SOCIAL_TYPE_GITHUB)
                                                                 ->get()
                                                                 ->first();

                        // 既に別のアカウントが紐付いている場合
                        if (!empty($social_account)) {
                            // 今回のアカウントでアップデート
                            $social_account->social_account_id = $github_account->id;
                            $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account->save();

                        } else {
                            // 今回のアカウントをインサート
                            $now = Carbon::now()->format('Y-m-d H:i:s');
                            $social_account = new Tr_user_social_accounts;
                            $social_account->user_id = $g_mail_user->id;
                            $social_account->social_account_id = $github_account->id;
                            $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_GITHUB;
                            $social_account->registration_date = $now;
                            $social_account->last_update_date = $now;
                            $social_account->save();
                        }

                        //　ログイン成功
                        $g_mail_user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
                        $g_mail_user->save();
                        CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $g_mail_user->id);

                    } else {
                        return redirect('/login')->with('custom_error_messages', [
                            'GitHubアカウントと同じメールアドレスを持つユーザが見つかりませんでした。
                            <a class="hover-thin" href="/login/sns/github?func=regist">こちら</a>から会員登録を行ってください。<br>
                            既に会員の方は、マイページよりGitHubアカウントの認証を行ってください。'
                        ]);
                    }
                }
            }
            // ログイン成功時はマイページに遷移
            return redirect('/user');

        // 会員登録機能
        } else if(!empty($oauth_func) && $oauth_func == 'regist') {

            // アカウント連携テーブルを取得
            $local_g_user_account = Tr_user_social_accounts::join('user_github_accounts', 'user_social_accounts.social_account_id', '=', 'user_github_accounts.id')
                                                           ->where('user_github_accounts.github_id', '=', $g_user->id)
                                                           ->where('user_social_accounts.social_account_type', '=', MdlUtil::SOCIAL_TYPE_GITHUB)
                                                           ->get();

            // アカウント連携テーブルに、既に該当のGitHubアカウントが存在した場合
            if (!$local_g_user_account->isEmpty()) {
                $this->log('error', 'github account already exist', [
                    'oauth_func' => $oauth_func,
                    'github_id' => $g_user->id,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    'GitHubアカウントは既に使用されています。'
                ]);
            }

            //　GitHub API からメールアドレスを取得できた場合
            if (!empty($g_user->email)) {

                // メールアドレスからユーザを取得
                $g_mail_user = Tr_users::getUserByMail($g_user->email)->get();

                if (!$g_mail_user->isEmpty()) {
                    $this->log('error', 'github email already exist', [
                        'oauth_func' => $oauth_func,
                        'github_id' => $g_user->id,
                        'email' => $g_user->email,
                    ]);
                    return redirect('/user/regist/auth')->with('custom_error_messages', [
                        '使用しているメールアドレスは既に登録されています。'
                    ]);
                }

                // メールアドレス認証フローを実行
                $auth_key = Tr_auth_keys::where('mail', $g_user->email)
                                        ->where('auth_task', MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT)
                                        ->first();

                if (empty($auth_key)) {
                    // 認証鍵テーブルにインサート
                    $auth_key = new Tr_auth_keys;
                    $auth_key->mail = $g_user->email;
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                    $auth_key->auth_task = MdlUtil::AUTH_TASK_REGIST_MAIL_AUHT;

                } else {
                    // 認証鍵テーブルにアップデート
                    $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                }
                $auth_key->ticket = FrntUtil::createUUID();
                $auth_key->save();


                $this->log('info', 'success to email auth', [
                    'oauth_func' => $oauth_func,
                    'github_id' => $g_user->id,
                    'email' => $g_user->email,
                ]);

                // 会員登録のユーザ情報入力画面に遷移
                return redirect('/user/regist?ticket='.$auth_key->ticket);

            // GitHub API からメールアドレスを取得できなかった場合
            } else {
                $this->log('info', 'github email not found', [
                    'oauth_func' => $oauth_func,
                    'github_id' => $g_user->id,
                    'email' => $g_user->email,
                ]);
                return redirect('/user/regist/auth')->with('custom_error_messages', [
                    '有効なGitHubアカウントが見つかりませんでした。'
                ]);
            }

        // マイぺージからの認証
        } else if(!empty($oauth_func) && $oauth_func == 'auth') {

            // ログインユーザ取得
            $login_user = FrntUtil::getFirstLoginUser();

            // ミドルウェアで検証してないので、ログイン中であることをここでチェック
            if (empty($login_user)) {
                return redirect('/');
            }

            // アカウントが別のユーザに紐付いている場合
            $other_account = Tr_user_social_accounts::where('user_id', '!=', $login_user->id)
                                                    ->where('social_account_type', MdlUtil::SOCIAL_TYPE_GITHUB)
                                                    ->where('social_account_id', $github_account->id)
                                                    ->get();
            if (!$other_account->isEmpty()) {
                return redirect('/user')->with('custom_error_messages', [
                    'ご利用のTwitterアカウントは既に使用されています。'
                ]);
            }

            // ユーザとソーシャルアカウントの紐付けテーブルを取得
            $social_account = Tr_user_social_accounts::getGithubAccount($login_user->id)->first();


            // 既に別のアカウントが紐付いている場合
            if (!empty($social_account)) {
                // 今回のアカウントでアップデート
                $social_account->social_account_id = $github_account->id;
                $social_account->last_update_date = Carbon::now()->format('Y-m-d H:i:s');
                $social_account->save();

            } else {
                // 今回のアカウントをインサート
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $social_account = new Tr_user_social_accounts;
                $social_account->user_id = $login_user->id;
                $social_account->social_account_id = $github_account->id;
                $social_account->social_account_type = MdlUtil::SOCIAL_TYPE_GITHUB;
                $social_account->registration_date = $now;
                $social_account->last_update_date = $now;
                $social_account->save();
            }
            return redirect('/user');
        }

        return redirect('/');
    }

    /**
     * 各種SNS認証解除
     * GET:/auth/sns/cancel
     *
     */
    public function deleteSNSAuth(Request $request){

        // ログインユーザを取得
        $login_user = FrntUtil::getFirstLoginUser();

        // social_typeパラメータを検証
        if (!(FrntUtil::validateSocialType($request->social_type ?: null))) {
            $this->log('error', 'Illegal operation');
            abort(400);
        }

        try {
            // SNS連携テーブルをデリート
            Tr_user_social_accounts::where('user_id', $login_user->id)
                                   ->where('social_account_type', $request->social_type)
                                   ->delete();

        } catch (Exception $e) {
            $this->log('error', 'failure to social auth delete', [
                'error' => $e->getMessage(),
            ]);
            abort(400, 'システムエラーが発生致しました。恐れ入りますが、しばらく時間をおいてから再度アクセスしてください。');
        }

        // マイぺージに戻る
        return redirect('/user');
    }
}
