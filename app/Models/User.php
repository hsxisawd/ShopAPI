<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable ,HasRoles;
    //默认看守器
    protected $guard_name='api';

    /**
     * 可批量分配的属性。
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'username',
        'phone',
        'wx_openid',
        'wx_unionid',
        'wx_token'
    ];

    /**
     * 应该为序列化隐藏的属性。
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    protected $casts=[
        'wx_token'=>'array'
    ];
    protected $appends=['headimg_url'];
    public function getHeadimgUrlAttribute()
    {
        return imgUrl($this->headimg);
    }





    /**
     * 获取将存储在 JWT 的主题声明中的标识符。
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回一个键值数组，其中包含要添加到 JWT 的任何自定义声明。
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
