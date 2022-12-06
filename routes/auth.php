<?php

$api=app('Dingo\Api\Routing\Router');
$api->version('v1',['middleware'=>'api.throttle','limit'=>60,'expires'=>1],function ($api){
//路由组
    $api->group(['prefix'=>'auth'],function ($api){
        //调试路由
        $api->post('test',[\App\Http\Controllers\Auth\RegisterController::class,'test']);
//注册
       $api->post('ENregister',[\App\Http\Controllers\Auth\RegisterController::class,'emailNameStore']);
 //注册验证码邮箱
       $api->post('register/code',[\App\Http\Controllers\Auth\RegisterController::class,'emailcode']);
//手机号注册
        $api->post('Pregister',[\App\Http\Controllers\Auth\RegisterController::class,'phone_store']);
//手机号绑定
        $api->post('bindphone',[\App\Http\Controllers\Auth\RegisterController::class,'bindphone']);
//邮箱用户名登录
        $api->post('ENlogin',[\App\Http\Controllers\Auth\LoginController::class,'emailNameLogin']);
//微信登录注册
        $api->post('wxLogin',[\App\Http\Controllers\Auth\RegisterController::class,'wx_store']);
//忘记密码，邮箱获取验证码
        $api->post('reset/password/email/code',[\App\Http\Controllers\Auth\PasswordResetController::class,'emailCode']);
//邮箱修改密码
        $api->post('reset/password/email/update',[\App\Http\Controllers\Auth\PasswordResetController::class,'resetPasswordByEmail']);
        //需要登录的路由
        $api->group(['middleware'=>'api.auth'],function ($api){
//        退出登录
            $api->post('logout',[\App\Http\Controllers\Auth\LoginController::class,'logout']);
//        刷新token
            $api->post('refresh',[\App\Http\Controllers\Auth\LoginController::class,'refresh']);
//         修改密码
            $api->post('password/update',[\App\Http\Controllers\Auth\PasswordController::class,'updatePassword']);
        });
    });

});


