<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CookieUtility as CkieUtil;

class Tr_slide_images extends Model
{
    //テーブル名
    protected $table = 'slide_images';
    //timestampの自動更新を明示的にOFF
    public $timestamps = false;
    //絶対に変更しないカラム(MassAssignmentExceptionエラー回避のため)
    protected $guarded = ['id'];

    /**
     * テーブル情報を昇順で取得
     */
    public function scopeGetSortSlideImage($query){
        return $query->orderBy('sort_order', 'asc')
                     ->get();
    }

    /**
     * 表示ステータス有効のみを昇順で取得
     */
    public function scopeGetDeleteFlagOFF($query){
        return $query->where('delete_flag', 'false')
                     ->orderBy('sort_order', 'asc')
                     ->get();
    }
}
