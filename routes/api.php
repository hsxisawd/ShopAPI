<?php
$params=[
    'middleware'=>[
        'api.throttle',
        'serializer:array',//减少transformer的包裹层
        'bindings'//支持路由模型的导入
    ],
    'limit'=>60,
    'expires'=>1
];
$api=app('Dingo\Api\Routing\Router');
//['middleware'=>'api.throttle','limit'=>60,'expires'=>1] 节流器

$api->version('v1',$params,function ($api){

    //首页数据
    $api->get('index',[\App\Http\Controllers\Api\IndexController::class,'index']);

    //商品详情
    $api->get('goods/{good}',[\App\Http\Controllers\Api\GoodsController::class,'show']);
    //商品列表
    $api->get('goods',[\App\Http\Controllers\Api\GoodsController::class,'index']);



    //需要认证token才能访问路由
    $api->group(['middleware'=>'api.auth'],function ($api){
        //用户详情
        $api->get('user',[\App\Http\Controllers\Api\UserController::class,'userInfo']);
        //更新用户信息
        $api->post('user',[\App\Http\Controllers\Api\UserController::class,'updateUserInfo']);

        //购物车管理
        $api->resource('carts',\App\Http\Controllers\Api\CartController::class, [
            'except'=>['show']
        ]);

        //订单预览
        $api->get('orders/preview',[\App\Http\Controllers\Api\OrderController::class,'preview']);
        //提交订单
        $api->post('orders',[\App\Http\Controllers\Api\OrderController::class,'store']);
        //订单列表
        $api->get('orders',[\App\Http\Controllers\Api\OrderController::class,'index']);

        //商品评论
        $api->post('orders/{orders}/comment',[\App\Http\Controllers\Api\CommentsController::class,'store']);

        //确认收货
        $api->patch('orders/{orders}/confirm',[\App\Http\Controllers\Api\OrderController::class,'confirm']);

        /**
         * 地址管理
         */
        //省县市地址
        $api->get('city',[\App\Http\Controllers\Api\CityController::class,'index']);

        //地址相关资源路由
        $api->resource('address',\App\Http\Controllers\Api\AddressController::class);
        // 设置为默认地址
        $api->patch('address/{address}/default',[\App\Http\Controllers\Api\AddressController::class,'default']);
    });
});


