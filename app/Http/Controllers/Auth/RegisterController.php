<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\EmailSendCode;
use App\Mail\seedcode;
use App\Models\User;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTAuth;

class RegisterController extends BaseController
{
    /*
     * 邮箱用户注册
     */
    public function emailNameStore(RegisterRequest $request)
    {
       //判断验证码匹不匹配
        $code = emailTree($request->input('email'),$request->input('code'));
        if (!$code) {
            return $this->response->errorBadRequest('验证码或者邮箱错误！');
        }
        //匹配的话删除验证码缓存
        Cache::forget('email_code'.$request->input('email'));
        //注册，返回错误信息，需要绑定手机号
        $request['password']=bcrypt($request->input('password'));
        $request['email_status']=0;
        return $request->except(['password_confirmation','code']);
    }
    //发送邮箱验证码
    public function emailcode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email=$request->input('email');
        Mail::to($email)
            ->queue(new EmailSendCode($email));
       return $this->response->noContent();
    }

    //微信登录注册
    public function wx_store(Request $request)
    {
        $request->validate([
            'wx_openid' => 'required',
            'wx_unionid' => 'required',
            'wx_token' => 'required'
        ]);
        $openid = $request->input('wx_openid');
        //判断是否已经注册过
        $wx_openid=User::where(['wx_openid'=>$openid])->first();
        if (!$wx_openid) {
            return $request;
        }
        //登录直接返回token信息
        $token = auth('api')->login($wx_openid);
        return $this->respondWithToken($token);
    }

    /**
     * 手机号注册绑定
     */
    public function phone_store(Request $request)
    {
        $request->validate([
           'phone'=>'required'

        ]);
        $user = new User();
        $phone = $request->input('phone');
        //判定手机号是否完成注册
        $Phone=User::where('phone',$phone)->first();
        if (!$Phone){
            $user->phone = $phone;
            $user->phone_status=0;
            $user->save();
            return $this->response()->noContent();
        }
        return $this->response->errorBadRequest('手机号已经注册！');

    }
    //绑定手机号
    public function bindphone(Request $request)
    {
        $request->validate([
            'phone'=>'required',
            'info'=>'required',
        ]);
        //获取缓存的绑定信息
        $phone=$request->input('phone');
        $info=$request->input('info');
        //判定手机号是否完成注册
        $Phone=User::where('phone',$phone)->first();
        User::when($Phone==false,function ($query) use ($phone){
            $user=new User();
            $user->phone = $phone;
            $user->phone_status=0;
            $user->save();
        })
        ->when($info,function ($query) use ($phone){
            $query->where('phone',$phone);
        })->update($info);

        //判断是邮箱绑定还是第三方绑定
        if (!empty($info['password'])){
            return $this->response()->noContent();
        }
        $token = auth('api')->login(User::where('phone',$phone)->first());
        return $this->respondWithToken($token);
    }

    /**
     * 格式化返回
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60*12
        ]);
    }
}

