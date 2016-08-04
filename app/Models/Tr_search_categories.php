<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_search_categories extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'search_categories';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * 親カテゴリーを取得
     */
    public function scopeGetParentCategories($query){
        return $query->where('parent_id', null)
                     ->get();
    }

    /**
     * 子カテゴリーを取得
     */
    public function scopeGetChildCategories($query){
        return $query->where('parent_id', '!=', null)
                     ->get();
    }
}
