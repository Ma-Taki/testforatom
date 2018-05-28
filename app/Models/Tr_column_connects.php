<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tr_link_column_connects;

class Tr_column_connects extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'column_connects';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
