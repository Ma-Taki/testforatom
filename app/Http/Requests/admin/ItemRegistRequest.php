<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class ItemRegistRequest extends Request
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
            'item_name' => 'required|max:50',
            'item_date_from' => 'required|date_format:Y/m/d',
            'item_date_to' => 'required|date_format:Y/m/d',
            'item_max_rate' => 'required',
            'item_rate_detail' => 'required|max:20',
            'areas' => 'required',
            'item_area_detail' => 'required|max:20',
            'item_employment_period' => 'max:50',
            'item_working_hours' => 'max:50',
            'search_categories' => 'max:20',
            'item_biz_category' => 'required',
            'job_types' => 'max:20',
            'sys_types' => 'max:20',
            'skills' => 'max:20',
            'item_tag' => '',
            'item_detail' => 'max:1000',
            'item_note' => 'max:1000',
        ];
    }
}
