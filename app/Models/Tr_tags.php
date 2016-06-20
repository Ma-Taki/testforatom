<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_tags extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'tags';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
