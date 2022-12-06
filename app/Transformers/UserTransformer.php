<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function  transform(User $user){
        return[
            'id'=> $user->id,
            'username'=>$user->username,
            'email'=>$user->email,
            'email_status'=>$user->email_status,
            'phone'=>$user->phone,
            'phone_status'=>$user->phone_status,
            'wx_openid'=>$user->wx_openid,
            'wx_unionid'=>$user->wx_unionid,
            'wx_token'=>$user->wx_token,
            'nickname'=>$user->nickname,
            'headimg'=>$user->headimg,
            'headimg_url'=>env('STORANG_URL').$user->headimg,
            'sex'=>$user->sex,
            'Personal_Introduction'=>$user->Personal_Introduction,
            "created_at"=> $user->created_at,
            "updated_at"=> $user->updated_at
        ];
}

}
