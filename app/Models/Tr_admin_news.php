<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_admin_news extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'admin_news';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = ['release_date'];

}
