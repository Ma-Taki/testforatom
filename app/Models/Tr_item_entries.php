<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_item_entries extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'item_entries';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = ['entry_date'];

    /* 紐付いたユーザ情報を取得する */
    public function user()
    {
        return $this->belongsTo('App\Models\Tr_users',
                                'user_id');
    }

    /* 紐付いた案件情報を取得する */
    public function item()
    {
        return $this->belongsTo('App\Models\Tr_items',
                                'item_id');
    }

}
