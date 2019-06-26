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
        // 正直不要
        return [
            'member_mail' => '',
            'member_name' => '',
            'member_name_kana' => '',
            'freeword' => '',
            'enabledOnly' => '',
            'impression' => '',
        ];
    }
}
