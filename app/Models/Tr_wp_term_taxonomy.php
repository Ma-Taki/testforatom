<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_wp_term_taxonomy extends Model
{
    protected $table = 'wp_term_taxonomy';

    /**
    * 用語を取得
    */
    public function term() {
        return $this->hasOne('App\Models\Tr_wp_terms','term_id','term_id');
    }
}
