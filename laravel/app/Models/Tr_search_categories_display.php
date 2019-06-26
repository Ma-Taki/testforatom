<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CookieUtility as CkieUtil;

class Tr_search_categories_display extends Model
{
    // モデルに関連付けるデータベースのテーブルを指定
    protected $table = 'search_categories_display';

    // timestampの自動更新を明示的にOFF
    public $timestamps = false;

    //絶対に変更しないカラム(MassAssignmentExceptionエラー回避のため)
    protected $guarded = ['id'];

}
