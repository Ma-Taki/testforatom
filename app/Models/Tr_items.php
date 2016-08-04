<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tr_items extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'items';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = [
        'registration_date',
        'service_start_date',
        'service_end_date'];

    /**
     * 配列を使っての複数代入を許可する項目
     *
     * @var array
     */
     protected $fillable = [
         'name',
         'biz_category_id',
         'service_start_date',
         'service_end_date',
         'registration_date',
         'last_update',
         'employment_period',
         'working_hours',
         'max_rate',
         'rate_detail',
         'area_detail',
         'detail',
         'delete_flag',
         'delete_date',
         'note',
         'version',
         'admin_id',
     ];

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

    /**
     * エントリーを取得
     */
     public function entries() {
         return $this->hasMany('App\Models\Tr_item_entries',
                               'item_id');
     }

     /**
      * 管理者を取得
      */
     public function adminUser() {
         return $this->belongsTo('App\Models\Tr_admin_user', 'admin_id');
     }

     /**
      * エントリー受付期間中
      */
      public function scopeEntryPossible($query)
      {
        $today = Carbon::today();
        return $query->where('delete_flag', '=', false)
                     ->where('service_start_date', '<=', $today)
                     ->where('service_end_date', '>=', $today);
      }
}
