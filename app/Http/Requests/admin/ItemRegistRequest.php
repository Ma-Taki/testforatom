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
            'item_rate_detail' => 'required',
            'areas' => 'required',
            'item_area_detail' => 'required',
            'item_employment_period' => '',
            'item_working_hours' => '',
            'search_categories' => '',
            'item_biz_category' => 'required',
            'job_types' => '',
            'sys_types' => '',
            'skills' => '',
            'item_tag' => '',
            'item_detail' => 'max:1000',
            'item_note' => 'max:1000',
        ];
    }
}
