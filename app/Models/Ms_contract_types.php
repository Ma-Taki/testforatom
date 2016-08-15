<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;

class Ms_contract_types extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'contract_types';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * indexOnly以外を取得
     */
    public function scopeGetNotIndexOnly($query){
        return $query->where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                     ->orderBy('sort_order', 'asc')
                     ->get();
    }

    /**
     * actualのみ取得
     */
    public function scopeGetActual($query){
        return $query->where('master_type', mdlUtil::MASTER_TYPE_ACTUAL_DATA)
                     ->orderBy('sort_order', 'asc')
                     ->get();
    }
}
