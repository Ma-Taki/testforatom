<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_user_twitter_accounts extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'user_twitter_accounts';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
