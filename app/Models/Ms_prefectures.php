<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ms_prefectures extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'prefectures';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
