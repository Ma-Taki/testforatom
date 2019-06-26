<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Tr_items;
use App\Models\Tr_link_items_sys_types;

class Ms_sys_types extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'sys_types';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * indexOnly以外・表示ステータスを昇順取得
     */
    public function scopeGetNotIndexOnly($query){
        return $query->where('delete_flag', false)
                     ->orderBy('sort_order', 'asc')
                     ->get();
    }

    /**
     * 1つの案件に登録されているシステム種別(表示ステータス)を昇順取得
     */
    public function scopeGetSysTypes($query,$id){
        return $query->select('sys_types.*')
                    ->join('link_items_sys_types', 'sys_types.id', '=', 'link_items_sys_types.sys_type_id')
                    ->join('items', 'items.id', '=', 'link_items_sys_types.item_id')
                    ->where('items.id', $id)
                    ->where('sys_types.delete_flag', false)
                    ->orderBy('sys_types.sort_order', 'asc')
                    ->get();
    }

    /**
     * 1つの案件に登録されているシステム種別(表示ステータス)を昇順取得
     */
    public function scopeSysTypes($query,$id){
        return $this->scopeGetSysTypes($query,$id);
    }
}
