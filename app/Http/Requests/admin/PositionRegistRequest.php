<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Ms_job_types;

/**
 * ポジション登録・編集用FormRequest
 *
 **/
class PositionRegistRequest extends Request
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
            'position_name'  => 'required|max:20'
        ];
    }
}
