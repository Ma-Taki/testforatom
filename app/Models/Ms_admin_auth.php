<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ms_admin_auth extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'admin_auth';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
