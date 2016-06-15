<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_admin_user extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'admin_user';

    // timestampのカラムがないため無効
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_name',
        'login_id',
        'password',
        'registration_date',
        'last_update_date',
        'last_login_date',
        'delete_flag',
    ];

    /**
     * 許可された権限を取得する
     */
    public function auths()
    {
        return $this->belongsToMany('App\Models\Ms_admin_auth',
                                    'link_admin_user_admin_auth',
                                    'admin_id',
                                    'auth_id');
    }

}
