<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_tag_infos extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'tag_infos';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;
}
