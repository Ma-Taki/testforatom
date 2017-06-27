<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;

class Tr_wp_term_relationships extends Model
{
    protected $table = 'wp_term_relationships';

    /**
    * 分類を取得
    */
    public function taxonomy() {
        return $this->hasOne('App\Models\Tr_wp_term_taxonomy', 'term_taxonomy_id', 'term_taxonomy_id');
    }
}
