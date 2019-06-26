<?php

namespace App\Http\Requests\front;

use App\Http\Requests\Request;

class EditEmailRequest extends Request
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
            'mail' => 'required|email|max:256|confirmed|unique:users,mail,NULL,id,delete_date,NULL',
        ];
    }
}
