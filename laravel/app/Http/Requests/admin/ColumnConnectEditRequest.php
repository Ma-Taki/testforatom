<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

/**
 * 記事紐付け登録/編集FormRequest
 *
 **/
class ColumnConnectEditRequest extends Request
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
            'connect_id' => 'required|max:3|unique:column_connects,connect_id,id',
            'title'      => 'required|max:50',
            'keyword'    => 'required|max:3000',
        ];
    }
}
