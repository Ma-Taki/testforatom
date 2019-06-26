<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Tr_items;
use App\Models\Tr_link_items_job_types;

class Ms_job_types extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'job_types';
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
     * 1つの案件に登録されているポジション(表示ステータス)を昇順取得
     */
    public function scopeGetJobTypes($query,$id){
        return $query->select('job_types.*')
                    ->join('link_items_job_types', 'job_types.id', '=', 'link_items_job_types.job_type_id')
                    ->join('items', 'items.id', '=', 'link_items_job_types.item_id')
                    ->where('items.id', $id)
                    ->where('job_types.delete_flag', false)
                    ->orderBy('job_types.sort_order', 'asc')
                    ->get();
    }

    /**
     * 1つの案件に登録されているポジション(表示ステータス)を昇順取得
     */
    public function scopeJobTypes($query,$id){
        return $this->scopeGetJobTypes($query,$id);
    }
}
