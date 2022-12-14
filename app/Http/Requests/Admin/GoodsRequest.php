<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;


class GoodsRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'title'=>'required|max:255',
            'category_id'=>'required',
            'description'=>'required|max:255',
            'price'=>'required|min:0',
            'stock'=>'required|min:0',
            'cover'=>'required',
            'pics'=>'required|array',
            'details'=>'required',
        ];
    }
}
