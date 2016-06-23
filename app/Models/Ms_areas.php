<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ms_areas extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'areas';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}