<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_users extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'users';

    // timestampのカラムがないため無効果
    public $timestamps = false;

    // 日付型で取得する項目
    protected $dates = ['birth_date', 'registration_date'];

    /**
     * 契約形態を取得
     */
    public function contractTypes() {
        return $this->belongsToMany('App\Models\Ms_contract_types',
                                    'link_users_contract_types',
                                    'user_id',
                                    'contract_type_id');
    }

    /**
     * 住所(都道府県)を取得
     */
    public function prefecture() {
        return $this->belongsTo('App\Models\Ms_prefectures',
                                'prefecture_id');
    }

    /**
     * エントリー一覧を取得
     */
    public function entries() {
        return $this->hasMany('App\Models\Tr_item_entries',
                              'user_id');
    }
}
