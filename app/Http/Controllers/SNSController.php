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
    public function getTwitterAuth() {
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

        // 認証成功
        $this->log('info', 'success to twitter auth', [
            'twitter_id' => $t_user->id,
            'accessToken' => $t_user->token,
        ]);

        // twitterアカウントに紐付いたユーザを取得
        $user = Tr_users::join('user_social_accounts', 'users.id', '=', 'user_social_accounts.user_id')
                        ->join('user_twitter_accounts', 'user_social_accounts.social_account_id', '=', 'user_twitter_accounts.id')
                        ->where('user_twitter_accounts.twitter_id', $t_user->id)
                        ->where('user_social_accounts.social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                        ->select('users.*')
                        ->enable()
                        ->get()
                        ->first();

        // 紐付いたユーザが存在する場合
        if (!empty($user)) {
            //　ログイン成功
            $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

        // 紐付いたユーザが存在しない場合
        } else {

            // twitterアカウント情報を取得
            $twitter_account = Tr_user_twitter_accounts::getAccountByTwitterId($t_user->id)
                                                       ->get()
                                                       ->first();

            // アカウント情報がテーブルに存在しない場合、インサートする
            if (empty($twitter_account)) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $twitter_account = new Tr_user_twitter_accounts;
                $twitter_account->twitter_id = $t_user->id;
                $twitter_account->access_token_key = $t_user->token;
                $twitter_account->access_token_secret = $t_user->tokenSecret;
                $twitter_account->registration_date = $now;
                $twitter_account->last_update_date = $now;
                $twitter_account->save();
            }

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

                // 会員未登録
                } else {
                    if (!empty($t_user->email)) {
                        // メールアドレス認証フローを実行
                        $auth_key = new Tr_auth_keys;
                        $auth_key->mail = $t_user->email;
                        $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                        $auth_key->auth_task = MdlUtil::AUTH_TASK_MAIL_AUHT;
                        $auth_key->ticket = FrntUtil::createUUID();
                        $auth_key->save();

                        // 会員登録のユーザ情報入力画面に遷移
                        return redirect('/user/regist?ticket='.$auth_key->ticket);

                    } else {
                        // 会員登録のメールアドレス認証画面に遷移
                        return redirect('/user/regist/auth')->with('custom_info_messages', ['Twitterアカウントでログインするためには、まずエンジニアルートで新規会員登録を行ってください。']);
                    }
                }
            }
        }

        // ログイン成功時はマイページに遷移
        return redirect('/user');
    }

    /**
     * Facebook認証
     * GET:/login/sns/facebook
     *
     */
    public function getFacebookAuth() {
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

        // 認証成功
        $this->log('info', 'success to facebook auth', [
            'facebook_id' => $f_user->id,
            'accessToken' => $f_user->token,
        ]);

        // facebookアカウントに紐付いたユーザを取得
        $user = Tr_users::join('user_social_accounts', 'users.id', '=', 'user_social_accounts.user_id')
                        ->join('user_facebook_accounts', 'user_social_accounts.social_account_id', '=', 'user_facebook_accounts.id')
                        ->where('user_facebook_accounts.facebook_id', $f_user->id)
                        ->where('user_social_accounts.social_account_type', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                        ->select('users.*')
                        ->enable()
                        ->get()
                        ->first();

        // 紐付いたユーザが存在する場合
        if (!empty($user)) {
            //　ログイン成功
            $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

        // 紐付いたユーザが存在しない場合
        } else {

            // facebookアカウント情報を取得
            $facebook_account = Tr_user_facebook_accounts::getAccountByFacebookId($f_user->id)
                                                         ->get()
                                                         ->first();

            // アカウント情報がテーブルに存在しない場合、インサートする
            if (empty($facebook_account)) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $facebook_account = new Tr_user_facebook_accounts;
                $facebook_account->facebook_id = $f_user->id;
                $facebook_account->registration_date = $now;
                $facebook_account->last_update_date = $now;
                $facebook_account->save();
            }

            // ログインユーザを取得
            $login_user = Tr_users::getLoginUser()->get()->first();

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
                if (!empty($t_mail_user)) {
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

                // 会員未登録
                } else {
                    if (!empty($f_user->email)) {
                        // メールアドレス認証フローを実行
                        $auth_key = new Tr_auth_keys;
                        $auth_key->mail = $f_user->email;
                        $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                        $auth_key->auth_task = MdlUtil::AUTH_TASK_MAIL_AUHT;
                        $auth_key->ticket = FrntUtil::createUUID();
                        $auth_key->save();

                        // 会員登録のユーザ情報入力画面に遷移
                        return redirect('/user/regist?ticket='.$auth_key->ticket);

                    } else {
                        // 会員登録のメールアドレス認証画面に遷移
                        return redirect('/user/regist/auth')->with('custom_info_messages', ['Facebookアカウントでログインするためには、まずエンジニアルートで新規会員登録を行ってください。']);
                    }
                }
            }
        }

        // ログイン成功時はマイページに遷移
        return redirect('/user');
    }

    /**
     * Github認証
     * GET:/login/sns/github
     */
    public function getGithubAuth() {
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

        // 認証成功
        $this->log('info', 'success to github auth', [
            'github_id' => $g_user->id,
            'accessToken' => $g_user->token,
        ]);

        // githubアカウントに紐付いたユーザを取得
        $user = Tr_users::join('user_social_accounts', 'users.id', '=', 'user_social_accounts.user_id')
                        ->join('user_github_accounts', 'user_social_accounts.social_account_id', '=', 'user_github_accounts.id')
                        ->where('user_github_accounts.github_id', $g_user->id)
                        ->where('user_social_accounts.social_account_type', MdlUtil::SOCIAL_TYPE_GITHUB)
                        ->select('users.*')
                        ->enable()
                        ->get()
                        ->first();

        // 紐付いたユーザが存在する場合
        if (!empty($user)) {
            //　ログイン成功
            $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

        // 紐付いたユーザが存在しない場合
        } else {
            // githubアカウント情報を取得
            $github_account = Tr_user_github_accounts::getFirstByGithubId($g_user->id)
                                                     ->get()
                                                     ->first();

            // アカウント情報がテーブルに存在しない場合、インサートする
            if (empty($github_account)) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $github_account = new Tr_user_github_accounts;
                $github_account->github_id = $g_user->id;
                $github_account->registration_date = $now;
                $github_account->last_update_date = $now;
                $github_account->save();
            }

            // ログインユーザを取得
            $login_user = Tr_users::getLoginUser()->get()->first();

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

                // 会員未登録
                } else {
                    if (!empty($g_user->email)) {
                        // メールアドレス認証フローを実行
                        $auth_key = new Tr_auth_keys;
                        $auth_key->mail = $g_user->email;
                        $auth_key->application_datetime = Carbon::now()->format('Y-m-d H:i:s');
                        $auth_key->auth_task = MdlUtil::AUTH_TASK_MAIL_AUHT;
                        $auth_key->ticket = FrntUtil::createUUID();
                        $auth_key->save();

                        // 会員登録のユーザ情報入力画面に遷移
                        return redirect('/user/regist?ticket='.$auth_key->ticket);

                    } else {
                        // 会員登録のメールアドレス認証画面に遷移
                        return redirect('/user/regist/auth')->with('custom_info_messages', ['GitHubアカウントでログインするためには、まずエンジニアルートで新規会員登録を行ってください。']);
                    }
                }
            }
        }

        // ログイン成功時はマイページに遷移
        return redirect('/user');
    }
}
