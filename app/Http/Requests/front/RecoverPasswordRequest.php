<?php

namespace App\Http\Requests\front;

use App\Http\Requests\Request;

class RecoverPasswordRequest extends Request
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
            'new_password' => [
                'required',
                'between:6,20',
                'confirmed',
                'regex:/^[\x21-\x7E]+$/',
            ],
        ];
    }
}
