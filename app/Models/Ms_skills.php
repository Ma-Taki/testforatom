<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Tr_items;
use App\Models\Tr_link_items_skills;

class Ms_skills extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'skills';
    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    /**
     * skill_category_idに紐づいた情報を取得
     */
    public function scopeGetSkills($query,$skill_category_id){
        return $query->where('skill_category_id', $skill_category_id)
        			 ->where('delete_flag', false)
                     ->orderBy('sort_order', 'asc')
                     ->get();
    }

    /**
     * 1つの案件に登録されているスキル(表示ステータス)を昇順取得
     */
    public function scopeGetItemSkills($query,$id){
        return $query->select('skills.*')
                    ->join('link_items_skills', 'skills.id', '=', 'link_items_skills.skill_id')
                    ->join('items', 'items.id', '=', 'link_items_skills.item_id')
                    ->where('items.id', $id)
                    ->where('skills.delete_flag', false)
                    ->orderBy('skills.sort_order', 'asc')
                    ->get();
    }

    /**
     * 1つの案件に登録されているスキル(表示ステータス)を昇順取得
     */
    public function scopeItemSkills($query,$id){
        return $this->scopeGetItemSkills($query,$id);
    }
}
