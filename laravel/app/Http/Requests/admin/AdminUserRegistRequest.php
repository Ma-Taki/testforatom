<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_admin_user;

/**
 * 管理者ユーザ新規登録用FormRequest
 *
 **/
class AdminUserRegistRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'admin_name' => 'required|max:30',
            // 論理削除済みのログインIDは再度登録可能
            'login_id' => 'required|between:8,20|unique:admin_user,login_id,'.$this->id.',id,delete_date,NULL',
            'password' => 'required|between:8,20',
            // 権限は一つ以上のチェックが必須
            'auths' => 'required',
        ];
    }
}
