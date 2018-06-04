<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_admin_news;

/**
 * 管理画面お知らせ用FormRequest
 *
 **/
class AdminNewsRegistRequest extends Request
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
            'release_date'  => 'required',
            'title'         => 'required|max:200',
            'contents'      => 'required',
        ];
    }
}
