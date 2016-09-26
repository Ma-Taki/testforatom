<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_user_twitter_accounts extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'user_twitter_accounts';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * access_tokenからレコードを一件取得する。
     *
     * @param QueryBuilder $query
     * @param string $access_token
     * @return QueryBuilder
     */
    public function scopeGetAccountByToken($query, $access_token) {
        return $query->where('access_token_key', $access_token);
    }

    /**
     * twitter_idからレコードを一件取得する。
     *
     * @param QueryBuilder $query
     * @param string $twitter_id
     * @return QueryBuilder
     */
    public function scopeGetAccountByTwitterId($query, $twitter_id) {
        return $query->where('twitter_id', $twitter_id);
    }
}
