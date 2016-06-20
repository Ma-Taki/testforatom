<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_link_admin_user_admin_auth extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'link_admin_user_admin_auth';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    // プライマリキー設定
    protected $primaryKey = ['admin_id', 'auth_id'];

    // incrementを無効化
    public $incrementing = false;

    /**
     * 配列を使っての複数代入を許可する項目
     *
     * @var array
     */
    protected $fillable = ['admin_id', 'auth_id'];

}
