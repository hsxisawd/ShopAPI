<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Transformers\UserInfoTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * 用户个人信息详情
     */
    public function userInfo()
    {
        return $this->response()->item(auth('api')->user(),new UserInfoTransformer());
    }

    /**
     * 更新用户信息
     */
    public function updateUserInfo(Request $request)
    {
        $request->validate([
           'nickname' =>'max:30',
            'sex'=>'max:1|min:0',
            'Personal_Introduction'=>'max:255'
        ],[
            'nickname.max'=>'昵称 不能超过30个字符',
            'sex.max'=>'性别 不能填超出1的数字',
            'sex.min'=>'性别 不能填小于0的数字',
            'Personal_Introduction.max'=>'个人简介 不能超过255个字符',
        ]);

        $user=auth('api')->user();
        $nickname=$request->input('nickname');
        $sex=intval($request->input('sex'));
        $headimg=$request->input('headimg');
        $Personal_Introduction=$request->input('Personal_Introduction');
        if ($nickname){
            $user->nickname=$nickname;
        }
        if ($sex || $sex===0){
            $user->sex=$sex;
        }
        if ($headimg){
            $user->headimg=$headimg;
        }
        if ($Personal_Introduction){
            $user->Personal_Introduction=$Personal_Introduction;
        }
        $user->save();
        return $this->response->noContent();
    }
}
