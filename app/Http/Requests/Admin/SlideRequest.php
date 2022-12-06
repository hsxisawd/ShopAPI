<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SlideRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'title'=>'required',
            'img'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'标题 不能为空',
            'img.required'=>'图片地址 不能为空',
        ];
    }
}
