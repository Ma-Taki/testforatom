<?php

namespace App\Http\Requests\front;

use App\Http\Requests\Request;

class UserEditRequest extends Request
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
            'last_name' => 'required|max:15',
            'first_name' => 'required|max:15',
            'last_name_kana' => [
                'required',
                'max:15',
                'regex:/^[ぁ-んー]+$/u'
            ],
            'first_name_kana' => [
                'required',
                'max:15',
                'regex:/^[ぁ-んー]+$/u'
            ],
            'gender' => 'required',
            'birth' => 'required|date',
            'birth_year' => 'required|numeric|between:1900,9999',
            'birth_month' => 'required|numeric|between:1,12',
            'birth_day' => 'required|numeric|between:1,31',
            'education' => '',
            'country' => '',
            'contract_types' => '',
            'prefecture' => '',
            'station' => '',
            // user_id はViewから送ってる
            /* メールアドレスの変更は別機能として実装
            'email' => 'required|email|max:256|confirmed|unique:users,mail,'.$this->user_id.',id,delete_date,NULL',
            'email_confirmation' => '',
            */
            'phone_num' => [
                'required',
                'max:14',
                'regex:/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/',
            ],
        ];
    }
}
