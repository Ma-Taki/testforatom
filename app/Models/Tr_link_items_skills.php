<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_link_items_skills extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'link_items_skills';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // プライマリキー設定
    protected $primaryKey = ['item_id', 'skill_id'];

    // incrementを無効化
    public $incrementing = false;

    /**
     * 配列を使っての複数代入を許可する項目
     *
     * @var array
     */
    protected $fillable = ['item_id', 'skill_id'];
}
