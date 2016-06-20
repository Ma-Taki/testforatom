<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ms_sys_types extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'sys_types';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
