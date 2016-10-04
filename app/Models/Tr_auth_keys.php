<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_auth_keys extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'auth_keys';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // 日付型で取得するカラム
    protected $dates = ['application_datetime'];
}
