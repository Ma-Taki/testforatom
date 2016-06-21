<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class MemberSearchRequest extends Request
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
        // validationいらないかも
        // 現行のは特にチェックないっぽい
        return [
            'member_mail' => '',
            'member_name' => '',
            'member_name_kana' => '',
        ];
    }
}
