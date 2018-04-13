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
                     ->where('delete_flag', false)
                     ->orderBy('parent_sort', 'asc')
                     ->get();
    }

    /**
     * 子カテゴリーを取得
     */
    public function scopeGetChildCategories($query){
        return $query->where('parent_id', '!=', null)
                     ->orderBy('child_sort', 'asc')
                     ->get();
    }

    /**
     * 親カテゴリIDに紐づく子カテゴリーを取得
     */
    public function scopeGetChildByParent($query, $id){
        return $query->where('parent_id', $id)
                     ->where('delete_flag', false)
                     ->orderBy('parent_sort', 'asc')
                     ->orderBy('child_sort', 'asc')
                     ->get();
    }

    /**
     * 親カテゴリーのみを昇順で取得
     */
    public function scopeParent($query){
        return $query->where('parent_id', null)
                    ->orderBy('parent_sort', 'asc');            
    }

    /**
     * 親カテゴリーを取得
     */
    public function scopeGetParentFlag($query, $id){
        return $query->where('id', $id)->get();
    }

}
