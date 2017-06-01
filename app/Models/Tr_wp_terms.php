<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;

class Tr_wp_terms extends Model
{
  protected $table = 'wp_terms';

  /**
   * 記事を取得
   */
  public function posts() {
      return $this->belongToMany('App\Models\Tr_wp_term_relationships','term_taxonomy_id','term_id');
  }

}
