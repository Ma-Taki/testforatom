<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests\front\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Tr_users;
class LoginController extends Controller
{
    /**
     * ログイン画面表示
     * GET:/front/login
     */
    public function index(){
        return view('front.login');
    }

    /**
     * ログイン処理
     * POST:/front/login
     */
    public function store(LoginRequest $request){

        // 入力値から、有効な管理ユーザを取得する
        $user = Tr_users::where('mail', $request->mail)
                        ->where('password', md5($request->password))
                        ->where('delete_flag', 0)
                        ->get();

        // DBに対象ユーザが存在しない、または削除済み
        if($user->isEmpty()){
            // 認証失敗
            return back()->with('custom_error_messages',['メールアドレス又はパスワードに誤りがあります。'])->withInput();

        }else{
            // TODO: ログイン成功時
            /*
            // 認証成功
            // sessionにユーザ情報を保存
            session([SessionUtility::SESSION_KEY_ADMIN_ID => $user->first()->id]);
            session([SessionUtility::SESSION_KEY_ADMIN_NAME => $user->first()->admin_name]);
            session([SessionUtility::SESSION_KEY_LOGIN_ID => $login_id]);
            // マスター管理者フラグ
            $adUser = Tr_link_admin_user_admin_auth::where('admin_id', $user->first()->id)
                                                   ->where('auth_id', 1)
                                                   ->get();
            if($adUser->isEmpty()){
                session([SessionUtility::SESSION_KEY_MASTER_FLG => false]);
            } else {
                session([SessionUtility::SESSION_KEY_MASTER_FLG => true]);
            }

            // 管理者テーブルをアップデート
            Tr_admin_user::where('id', $user->first()->id)
                         ->update([
                'last_login_date' => date('Y-m-d H:i:s', time()),
                ]);
            return redirect('/admin/top');
            */
        }

        return view('front.top');
    }
}
