<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tr_wp_posts extends Model
{
  protected $table = 'wp_posts';
  protected $dates = ['post_date'];

  /**
   * メタ情報を取得（アイキャッチ取得に使う）
   */
  public function metas() {
      return $this->hasMany('App\Models\Tr_wp_postmeta','post_id','ID');
  }

  /**
   * 所属カテゴリーを取得
   */
  public function categories() {
      return $this->hasMany('App\Models\Tr_wp_term_relationships','object_id','ID');
  }



}
