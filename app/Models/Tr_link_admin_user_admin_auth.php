<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_link_admin_user_admin_auth extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'link_admin_user_admin_auth';

    // プライマリキー設定
    protected $primaryKey = ['admin_id', 'auth_id'];
    // increment無効化
    public $incrementing = false;

    protected $fillable = ['admin_id', 'auth_id'];

    public $timestamps = false;
}
