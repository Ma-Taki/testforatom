<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_user_social_accounts extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'user_social_accounts';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
