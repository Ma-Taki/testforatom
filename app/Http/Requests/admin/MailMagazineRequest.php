<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class MailMagazineRequest extends Request
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
            'subject' => 'required',
            'mailText' => 'required',
            'toAddressesFlag' => 'required',
            'toAddresses' => 'required_if:toAddressesFlag,2|email_array',
            'ccAddresses' => 'email_array',
            'bccAddresses' => 'email_array',
            'sendDateFlag' => 'required',
            'sendDateTime' => ['required_if:sendDateFlag,1','date','regex:/0$/'],
        ];
    }

    public function messages() {
        return [
            'sendDateTime.regex' => '送信日時は１０分単位で指定する必要があります',
        ];
    }

}
