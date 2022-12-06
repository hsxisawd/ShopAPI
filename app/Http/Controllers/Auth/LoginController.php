<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class LoginController extends BaseController
{

    /**
     * 邮箱用户名登录
     */
    public function emailNameLogin(LoginRequest $request)
    {
        $credentials=['password'=>$request->input('password')];
        $Account= $request->input('account');
        $test=User::select('email','username','phone')
            ->where('email',$Account)
            ->orwhere('username',$Account)
            ->orwhere('phone',$Account)
            ->first();
        if (!$test) return $this->response()->errorBadRequest('邮箱或者用户名不存在！');

        //判断用户名或者邮箱是否存在

        if($test['email']==$Account){
            $credentials['email']=$Account;
        }elseif ($test['username']==$Account){
            $credentials['username']=$Account;
        }elseif($test['phone']==$Account){
            $credentials['phone']=$Account;
        }

        $token = auth('api')->attempt($credentials);
        if (!$token) return $this->response->errorForbidden('验证错误！');
        //检查用户状态
        $user=auth('api')->user();

        if($user->is_locked==1){
            return $this->response->errorForbidden('被锁定');
        }
        return $this->respondWithToken($token);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        auth('api')->logout();

        return $this->response->noContent();
    }

    /**
     *刷新Token
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
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

