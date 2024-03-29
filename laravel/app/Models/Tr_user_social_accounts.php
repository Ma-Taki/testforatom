<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as MdlUtil;

class Tr_user_social_accounts extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'user_social_accounts';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    public function scopeGetFacebookAccount($query, $user_id) {
        return $query->where('user_id', $user_id)
                     ->where('social_account_type', MdlUtil::SOCIAL_TYPE_FACEBOOK)
                     ->get();
    }

    public function scopeGetTwitterAccount($query, $user_id) {
        return $query->where('user_id', $user_id)
                     ->where('social_account_type', MdlUtil::SOCIAL_TYPE_TWITTER)
                     ->get();
    }

    public function scopeGetGithubAccount($query, $user_id) {
        return $query->where('user_id', $user_id)
                     ->where('social_account_type', MdlUtil::SOCIAL_TYPE_GITHUB)
                     ->get();
    }
}
