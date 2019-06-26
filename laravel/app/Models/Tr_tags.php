<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tr_link_items_tags;
use App\Models\Tr_items;

class Tr_tags extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'tags';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;


    /**
     * 案件を取得
     */
    public function items() {
        return $this->belongsToMany('App\Models\Tr_items',
                                    'link_items_tags',
                                    'tag_id',
                                    'item_id');
    }

    /**
     * 配列を使っての複数代入を許可する項目
     *
     * @var array
     */
    protected $fillable = [
        'term',
    ];
}
