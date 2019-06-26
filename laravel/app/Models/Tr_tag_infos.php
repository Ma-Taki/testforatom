<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_tag_infos extends Model
{
    // 主キーを明示
    protected $primaryKey = 'tag_id';

    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'tag_infos';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * タグを取得
     */
    public function tag(){
     return $this->hasOne('App\Models\Tr_tags',
                          'id',
                          'tag_id');
    }

    /**
     * 特集タグを取得
     */
    public function scopeGetFeatureTagInfo($query) {
        return $query->where('tag_type', 3)
                     ->orderBy('sort_order', 'asc')
                     ->limit(30)
                     ->get();
    }

    /**
     * ピックアップタグを取得
     */
    public function scopeGetPickupTagInfo($query) {
        return $query->where('tag_type', 2)
                     ->orderBy('sort_order', 'asc')
                     ->limit(30)
                     ->get();
    }
}
