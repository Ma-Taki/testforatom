<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Tr_user_twitter_accounts;
use App\Models\Tr_user_social_accounts;
use App\Models\Tr_users;
use App\Libraries\ModelUtility as MdlUtil;
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
            $tuser = Socialite::driver('twitter')->user();

        } catch (\Exception $e) {
            // 認証失敗
            $this->log('error', 'failure to Twitter Login');
            return redirect("/");
        }

        // 例外が発生しなかった時点で、絶対に空ではないとは思う
        if (empty($tuser)) return redirect("/");

        // 認証成功
        $this->log('info', 'success to Twitter Login ', [
            'twitter_id' => $tuser->id,
            'accessToken' => $tuser->token,
        ]);

        // Twitterアカウントに紐付いたユーザを取得
        $user = Tr_users::join('user_social_accounts', 'users.id', '=', 'user_social_accounts.user_id')
                        ->join('user_twitter_accounts', 'user_social_accounts.social_account_id', '=', 'user_twitter_accounts.id')
                        ->where('user_twitter_accounts.twitter_id', $tuser->id)
                        ->where('user_social_accounts.social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                        ->select('users.*')
                        ->get()
                        ->first();

        // Twitterアカウントに紐付いたユーザが存在する場合
        if (!empty($user)) {
            //　ログイン成功
            $user->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            CkieUtil::set(CkieUtil::COOKIE_NAME_USER_ID, $user->id);

        // Twitterアカウントに紐付いたユーザが存在しない場合
        } else {
            // Twitterアカウント情報を取得
            $twitter_account = Tr_user_twitter_accounts::where('twitter_id', $tuser->id)
                                                       ->get()
                                                       ->first();

            // Twitterアカウント情報がテーブルに存在しない場合、インサートする
            if (empty($twitter_account)) {
                $now = Carbon::now()->format('Y-m-d H:i:s');
                $twitter_account = new Tr_user_twitter_accounts;
                $twitter_account->twitter_id = $tuser->id;
                $twitter_account->access_token_key = $tuser->token;
                $twitter_account->access_token_secret = $tuser->tokenSecret;
                $twitter_account->registration_date = $now;
                $twitter_account->last_update_date = $now;
                $twitter_account->save();
            }

            // ログインユーザを取得
            $login_user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                                  ->enable()
                                  ->get()
                                  ->first();

            // ログイン中の場合
            if(!empty($login_user)) {
                // ユーザとソーシャルアカウントの紐付けテーブルを取得
                $social_account = Tr_user_social_accounts::where('user_id', $login_user->id)
                                                         ->where('social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                                                         ->get()
                                                         ->first();

                // 既に別のTwitterアカウントが紐付いている場合
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

            } else {
                // 一般的な新規会員登録のフローに飛ばす
                return redirect('/user/regist/auth')->with('custom_info_messages', ['Twitterアカウントでログインするためには、まずエンジニアルートで新規会員登録を行ってください。<br>エンジニアルートにログインした状態でもう一度認証を行うことで、Twitterアカウントでのログインが可能となります。']);
            }
        }

        return redirect('/user');
    }
}
