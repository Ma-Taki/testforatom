<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;

class Ms_job_types extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'job_types';
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
}
