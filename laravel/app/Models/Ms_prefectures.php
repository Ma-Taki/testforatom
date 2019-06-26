<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;

class Ms_prefectures extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'prefectures';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * indexOnly以外かつ、国内に存在している都道府県を取得
     */
    public function scopeGetNotIndexOnly($query){
        return $query->where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                     ->where('real_existence', true)
                     ->orderBy('id', 'asc')
                     ->get();
    }
}
