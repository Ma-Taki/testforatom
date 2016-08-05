<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Libraries\FrontUtility as FrntUtil;

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
      public function scopeEntryPossible($query) {
        $today = Carbon::today();
        return $query->where('delete_flag', '=', false)
                     ->where('service_start_date', '<=', $today)
                     ->where('service_end_date', '>=', $today);
      }

    /**
     * スキルIDを配列で受け取り、一つ以上該当の要求スキルを持つ案件を返す。
     *
     * @param QueryBuilder $query
     * @param array $skills
     * @return QueryBuilder
     */
    public function scopeGetItemBySkills($query, $skills) {
        return $query->when(!empty($skills), function($query) use ($skills) {
            return $query->join('link_items_skills', 'items.id', '=', 'link_items_skills.item_id')
                         ->join('skills', 'skills.id', '=', 'link_items_skills.skill_id')
                         ->where(function($query) use ($skills) {
                foreach ((array)$skills as $skill) {
                    $query->orWhere('skills.id', $skill);
                }
            });
        });
    }

    /**
     * システム種別IDを配列で受け取り、一つ以上該当のシステム種別を持つ案件を返す。
     *
     * @param QueryBuilder $query
     * @param array $sys_types
     * @return QueryBuilder
     */
    public function scopeGetItemBySysTypes($query, $sys_types) {
        return $query->when(empty(!$sys_types), function($query) use ($sys_types) {
            return $query->join('link_items_sys_types', 'items.id', '=', 'link_items_sys_types.item_id')
                         ->join('sys_types', 'sys_types.id', '=', 'link_items_sys_types.sys_type_id')
                         ->where(function($query) use ($sys_types) {
                foreach ((array)$sys_types as $sys_type) {
                    $query->orWhere('sys_types.id', $sys_type);
                }
            });
        });
    }

    /**
     * 指定された報酬額以上の案件を返す。
     *
     * @param QueryBuilder $query
     * @param int $search_rate
     * @return QueryBuilder
     */
    public function scopeGetItemByRate($query, $search_rate) {
        return $query->when(array_key_exists($search_rate, FrntUtil::SEARCH_CONDITION_RATE),
            function($query) use ($search_rate) {
                return $query->where('items.max_rate', '>=', $search_rate);
        });
    }

    /**
     * 業種IDを配列で受け取り、該当の業種を持つ案件を返す。
     *
     * @param QueryBuilder $query
     * @param array $biz_categories
     * @return QueryBuilder
     */
    public function scopeGetItemByBizCategories($query, $biz_categories) {
        return $query->when(empty(!$biz_categories), function($query) use ($biz_categories) {
            return $query->where(function($query) use ($biz_categories) {
                foreach ((array)$biz_categories as $biz_category) {
                    $query->orWhere('items.biz_category_id', $biz_category);
                }
            });
        });
    }

    /**
     * エリアIDを配列で受け取り、一つ以上該当のエリアを持つ案件を返す。
     *
     * @param QueryBuilder $query
     * @param array $areas
     * @return QueryBuilder
     */
    public function scopeGetItemByAreas($query, $areas) {
        return $query->when(empty(!$areas), function($query) use ($areas) {
            return $query->join('link_items_areas', 'items.id', '=', 'link_items_areas.item_id')
                         ->join('areas', 'areas.id', '=', 'link_items_areas.areas_id')
                         ->where(function($query) use ($areas) {
                foreach ((array)$areas as $area) {
                    $query->orWhere('area.id', $area);
                }
            });
        });
    }

    /**
     * ポジションIDを配列で受け取り、一つ以上該当のポジションを持つ案件を返す。
     *
     * @param QueryBuilder $query
     * @param array $job_types
     * @return QueryBuilder
     */
    public function scopeGetItemByJobTypes($query, $job_types) {
        return $query->when(empty(!$job_types), function($query) use ($job_types) {
            return $query->join('link_items_job_types', 'items.id', '=', 'link_items_job_types.item_id')
                         ->join('job_types', 'job_types.id', '=', 'link_items_job_types.job_type_id')
                         ->where(function($query) use ($job_types) {
                foreach ((array)$job_types as $job_type) {
                    $query->orWhere('job_types.id', $job_type);
                }
            });
        });
    }
}
