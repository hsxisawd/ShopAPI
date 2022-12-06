<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        表单验证
        return [
            'username'=>'required|max:16',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|max:16|confirmed',
        ];
    }
}
