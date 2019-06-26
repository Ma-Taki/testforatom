<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Ms_skills;

/**
 * スキル登録・編集用FormRequest
 *
 **/
class SkillRegistRequest extends Request
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
            'skill_name'  => 'required|max:20'
        ];
    }
}
