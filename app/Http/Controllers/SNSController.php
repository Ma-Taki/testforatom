<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SNSController extends Controller
{
    /**
     * Twitter認証
     * GET:/login/sns/twitter
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
            return redirect("/");
        }
        if ($tuser) {
            dd($tuser);
        } else {
            return 'something went wrong';
        }
    }
}
