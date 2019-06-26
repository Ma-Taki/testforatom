<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_link_column_connects extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'link_column_connects';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

   // プライマリキー設定
    protected $primaryKey = ['connect_id', 'keyword'];

    // incrementを無効化
    public $incrementing = false;

    /**
     * キーワード検索
     */
    // public function scopeSearchKeyword($query,$keyword) {
    //     return $query->whereIn('keyword', $keyword)
    //                  ->get();
    // }
}
