<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Tr_slide_images;

/**
 * スライド画像更新用FormRequest
 *
 **/
class SlideImgEditRequest extends Request
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
            'image_title' => 'required|max:30',
            'image_link'  => 'required|max:100',
            'image_sort'  => '',
            'image_file'  => 'dimensions:width=1000,height=320',
        ];
    }
}
