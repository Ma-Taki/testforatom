<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class EntrySearchRequest extends Request
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
            'entry_id' => '', // 0~9の正規表現
            'entry_date_from' => 'date', // コントローラで使いのチェックあり
            'entry_date_to' => 'date',   // コントローラで使いのチェックあり
        ];
    }
}
