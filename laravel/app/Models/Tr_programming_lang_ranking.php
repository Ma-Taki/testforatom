<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_programming_lang_ranking extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'programming_lang_ranking';

    // // プライマリキー設定
    // protected $primaryKey = [];

    // incrementを無効化
    public $incrementing = false;

    // /**
    //  * 配列を使っての複数代入を許可する項目
    //  *
    //  * @var array
    //  */
    // protected $fillable = [];

}