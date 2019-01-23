<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CookieUtility as CkieUtil;

class Tr_users extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'users';

    // timestampのカラムがないため無効果
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = ['birth_date', 'registration_date'];

    /**
     * 契約形態を取得
     */
    public function contractTypes() {
        return $this->belongsToMany('App\Models\Ms_contract_types',
                                    'link_users_contract_types',
                                    'user_id',
                                    'contract_type_id');
    }

    /**
     * 住所(都道府県)を取得
     */
    public function prefecture() {
        return $this->belongsTo('App\Models\Ms_prefectures',
                                'prefecture_id');
    }

    /**
     * エントリー一覧を取得
     */
    public function entries() {
        return $this->hasMany('App\Models\Tr_item_entries',
                              'user_id');
    }

    /**
     * ソーシャルアカウントを取得
     */
    public function socialAccount() {
        return $this->hasMany('App\Models\Tr_user_social_accounts',
                              'user_id');
    }

    /**
     * 有効なユーザー
     */
    public function scopeEnable($query) {
        return $query->where('delete_flag', 0)
                     ->where('delete_date', null);
    }

    /**
     * 無効なユーザー
     */
    public function scopeDisable($query) {
        return $query->where('delete_flag', '>', 0)
                     ->where('delete_date', '!=', null);
    }

    /**
     * ログイン中ユーザを取得
     */
    public function scopeGetLoginUser($query) {
        return $query->where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                     ->where('delete_flag', 0)
                     ->where('delete_date', null);
    }

    /**
     * メールアドレスから有効なユーザを1件取得
     */
    public function scopeGetUserByMail($query, $email) {
        return $query->where('mail', $email)
                     ->where('delete_flag', 0)
                     ->where('delete_date', null);
    }

    /**
     * SNSアカウントに紐付いた有効なユーザを取得
     */
    public function scopeGetUserBySnsAccount($query, $account) {
        return $query->join('user_social_accounts', 'users.id', '=', 'user_social_accounts.user_id')
                     ->join('user_'.$account['name'].'_accounts', 'user_social_accounts.social_account_id', '=', 'user_'.$account['name'].'_accounts.'.$account['name'].'_id')
                     ->where('user_'.$account['name'].'_accounts.'.$account['name'].'_id', $account['id'])
                     ->where('user_social_accounts.social_account_type', $account['type'])
                     ->where('delete_flag', 0)
                     ->where('delete_date', null)
                     ->select('users.*');
    }

    /**
     * メルマガ配信希望者ユーザを取得
     */
    public function scopeGetUserMailMagazine($query) {
        return $query->where('magazine_flag', 1)
                     ->where('delete_flag', 0)
                     ->where('delete_date', null);
    }


}
