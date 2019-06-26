<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_search_categories;

/**
 * カテゴリー登録用FormRequest
 *
 **/
class CategoryRegistRequest extends Request
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
            'category_name'     => 'required|max:20',
            'page_title'        => 'required|max:50',
            'page_keywords'     => 'required|max:255',
            'page_description'  => 'required|max:255',
        ];
    }
}
