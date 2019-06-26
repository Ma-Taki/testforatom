<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Ms_sys_types;

/**
 * システム種別登録・編集用FormRequest
 *
 **/
class SystemTypeRegistRequest extends Request
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
            'sysType_name'  => 'required|max:20'
        ];
    }
}
