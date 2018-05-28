<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_Column_connects;

/**
 * 記事紐付け登録用FormRequest
 *
 **/
class ColumnConnectRegistRequest extends Request
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
            'connect_id' => 'required|max:3',
            'title'      => 'required|max:50',
            'keyword'    => 'required|max:3000',
        ];
    }
}
