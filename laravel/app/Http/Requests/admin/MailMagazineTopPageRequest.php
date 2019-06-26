<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class MailMagazineTopPageRequest extends Request
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
          'type' => 'required|in:new,edit',
          'id' => 'required_if:type,edit|integer|exists:mail_magazines,id',
        ];
    }

    public function messages() {
        return [
            'type.require' => '不正なURLです',
            'type.in' => '不正なURLです',
            'id.exists' => 'URLに存在しないメルマガIDが含まれています',
            'id.integer' => 'メルマガIDは数字のみ有効です'
        ];
    }
}
