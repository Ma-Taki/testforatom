<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Ms_skill_categories;

/**
 * スキルカテゴリー登録・編集用FormRequest
 *
 **/
class SkillCategoryRegistRequest extends Request
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
            'skillCategory_name'  => 'required|max:20'
        ];
    }
}
