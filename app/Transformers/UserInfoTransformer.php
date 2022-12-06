<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserInfoTransformer extends TransformerAbstract
{
    public function  transform(User $user){
        return[
            'id'=> $user->id,
            'username'=>$user->username,
            'email'=>$user->email,
            'phone'=>$user->phone,
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
