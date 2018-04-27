<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;

class Ms_skill_categories extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'skill_categories';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * スキルを取得
     */
    public function skills() {
        return $this->hasMany('App\Models\Ms_skills',
                              'skill_category_id',
                              'id');
    }

    /**
     * indexOnly以外を取得
     */
     public function scopeGetNotIndexOnly($query){
         return $query->where('delete_flag', false)
                      ->orderBy('sort_order', 'asc')
                      ->get();
     }

    /**
     * indexOnly以外を取得
     */
     public function scopeGetSkillCategoryFlag($query,$id){
            return $query->where('id', $id)
                      ->get();

     }
}













