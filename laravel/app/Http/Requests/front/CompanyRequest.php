<?php

namespace App\Http\Requests\front;

use App\Http\Requests\Request;

class CompanyRequest extends Request
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
            'contact_type' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'last_name_kana' => 'required',
            'first_name_kana' => 'required',
            'company_name' => '',
            'department_name' => '',
            'post_num' => '',
            'address' => '',
            'phone_num' => 'required',
            'mail' => 'required|email|confirmed',
            'url' => '',
            'contactMessage' => 'required',
        ];
    }
}
