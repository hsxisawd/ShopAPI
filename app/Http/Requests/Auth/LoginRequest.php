<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'account'=>'required',
            'password'=>'required|min:6|max:16',
        ];
    }
    public function messages()
    {
        return [
            'account.required'=>'邮箱/手机号/用户名 不能为空！'
        ];
    }
}
