<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_search_categories extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'search_categories';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
