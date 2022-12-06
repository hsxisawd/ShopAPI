<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
        用户列表
     */
    public function index(Request $request)
    {
        $name=$request->input('name');
        $email=$request->input('email');
        #搜索 判断name存在不
        $users=User::when($name,function ($query) use($name){
            $query->where('name','like',"%$name%");#模糊搜索
        })->when($email,function ($query) use($email){
            $query->where('email',$email);#全匹配搜索
        })  #分页返回
            ->paginate(2);

        return $this->response->paginator($users,new UserTransformer());
    }


    /**
    用户详情
     */
    public function show(User $user)
    {
        return $this->response->item($user,new UserTransformer());
    }

    /**
     * 启用禁用用户
    */
    public function lock(User $user)
    {
        //获取user对象，判断is_locked是否为0，不是就等1，如果原来是1就等于0
        $user->is_locked=$user->is_locked==0?1:0;
        $user->save();
        return $this->response->noContent();
    }

}
