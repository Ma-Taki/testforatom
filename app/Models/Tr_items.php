<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_items extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'items';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = ['service_start_date', 'service_end_date'];

    /**
     * 業種を取得
     */
    public function bizCategorie() {
        return $this->belongsTo('App\Models\Ms_biz_categories', 'biz_category_id');
    }

    /**
     * 検索カテゴリーを取得
     */
    public function searchCategorys() {
        return $this->belongsToMany('App\Models\Tr_search_categories',
                                    'link_items_search_categories',
                                    'item_id',
                                    'search_category_id');
    }

    /**
     * エリアを取得
     */
    public function areas() {
        return $this->belongsToMany('App\Models\Ms_areas',
                                    'link_items_areas',
                                    'item_id',
                                    'area_id');
    }

    /**
     * システム種別を取得
     */
    public function sysTypes() {
        return $this->belongsToMany('App\Models\Ms_sys_types',
                                    'link_items_sys_types',
                                    'item_id',
                                    'sys_type_id');
    }

    /**
     * 職種を取得
     */
    public function jobTypes() {
        return $this->belongsToMany('App\Models\Ms_job_types',
                                    'link_items_job_types',
                                    'item_id',
                                    'job_type_id');
    }


    /**
     * スキルを取得
     */
    public function skills() {
        return $this->belongsToMany('App\Models\Ms_skills',
                                    'link_items_skills',
                                    'item_id',
                                    'skill_id');
    }

    /**
     * タグを取得
     */
    public function tags() {
        return $this->belongsToMany('App\Models\Tr_tags',
                                    'link_items_tags',
                                    'item_id',
                                    'tag_id');
    }




}
