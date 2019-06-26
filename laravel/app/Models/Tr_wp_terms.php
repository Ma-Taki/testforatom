<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;

class Tr_wp_terms extends Model
{
    protected $table = 'wp_terms';

    /**
     * 分類を取得
     */
    public function taxonomies() {
        return $this->hasMany('App\Models\Tr_wp_term_taxonomy', 'term_id', 'term_id');
    }

    /**
    * 記事を取得
    */
    public function posts() {
        return $this->belongToMany('App\Models\Tr_wp_term_relationships','term_taxonomy_id','term_id');
    }

    /**
     * すべてのカテゴリーを取得
     */
    public function scopeGetAllCategories($query) {

        return $query->get()->filter(function($v, $k) {
            foreach ($v->taxonomies as $taxonomy) {
                if ($taxonomy->taxonomy === 'category') return true;
            }
            return false;
        });
    }
}
