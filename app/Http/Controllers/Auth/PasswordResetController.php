<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\EmailSendCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends BaseController
{
    //发送邮箱验证码
    public function emailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        $email=$request->input('email');
        Mail::to($email)
            ->queue(new EmailSendCode($email));
        return $this->response->noContent();
    }

    public function resetPasswordByEmail(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required|confirmed'
        ]);
        //判断验证码匹不匹配
        $code = emailTree($request->input('email'),$request->input('code'));
        if (!$code) {
            return $this->response->errorBadRequest('验证码或者邮箱错误！');
        }

        //更新密码
        $user=User::where('email',$request->email)->first();
        $user->password=bcrypt($request->input('password'));
        $user->save();

        return $this->response->noContent();
    }

}
