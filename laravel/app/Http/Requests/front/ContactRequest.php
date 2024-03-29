<?php

namespace App\Http\Requests\front;

use App\Http\Requests\Request;

class ContactRequest extends Request
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
            'last_name' => 'required',
            'first_name' => 'required',
            'last_name_kana' => 'required',
            'first_name_kana' => 'required',
            'company_name' => '',
            'mail' => 'required|email|confirmed',
            'mail_confirmation' => '',
            'contactMessage' => 'required',
        ];
    }
}
