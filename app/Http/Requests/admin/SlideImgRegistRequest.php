<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_slide_images;

/**
 * スライド画像登録用FormRequest
 *
 **/
class SlideImgRegistRequest extends Request
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
            'image_title' => 'required',
            'image_link'  => 'required',
            'image_file'  => 'required|dimensions:width=1000,height=320',
            'image_sort'  => 'required',
        ];
    }
}
