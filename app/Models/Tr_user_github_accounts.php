<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tr_user_github_accounts extends Model
{
    protected $table = 'user_github_accounts';
    public $timestamps = false;

    /**
     * github_idからレコードを一件取得する。
     *
     * @param QueryBuilder $query
     * @param int $github_id
     * @return QueryBuilder
     */
    public function scopeGetFirstByGithubId($query, $github_id) {
        return $query->where('github_id', $github_id);
    }
}
