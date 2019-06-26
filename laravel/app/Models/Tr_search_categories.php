<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tr_items;
use App\Models\Tr_link_items_search_categories;

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

    /**
     * 1つの案件に登録されているカテゴリー(表示ステータス)を昇順取得
     */
    public function scopeGetItemCategories($query,$id){
        return $query->select('search_categories.*')
                    ->join('link_items_search_categories', 'search_categories.id', '=', 'link_items_search_categories.search_category_id')
                    ->join('items', 'items.id', '=', 'link_items_search_categories.item_id')
                    ->where('items.id', $id)
                    ->where('search_categories.delete_flag', false)
                    ->orderBy('search_categories.parent_sort', 'asc')
                    ->orderBy('search_categories.child_sort', 'asc')
                    ->get();
    }
}
