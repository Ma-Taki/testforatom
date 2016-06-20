<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ms_skills extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'skills';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
