<?php

$api=app('Dingo\Api\Routing\Router');
$params=[
    'middleware'=>[
            'api.throttle',
            'serializer:array',//减少transformer的包裹层
            'bindings'//支持路由模型的导入
    ],
    'limit'=>60,
    'expires'=>1
];
$api->version('v1',$params,function ($api){
    $api->group(['middleware'=>'api.auth'],function ($api){
        $api->get('files/get',[\App\Http\Controllers\file\fileController::class,'get_files_url']);
        $api->post('files/up',[\App\Http\Controllers\file\fileController::class,'up_files']);
    });
    $api->group(['prefix'=>'admin'],function ($api){
        //需要登录验证的路由
        $api->group(['middleware'=>['api.auth','check.permission']],function ($api){
            //禁用用户/启用用户
            $api->patch('users/{user}/lock',[\App\Http\Controllers\Admin\UserController::class,'lock'])->name('user.lock');
            //用户管理资源路由
            $api->resource('users',\App\Http\Controllers\Admin\UserController::class,[
                //只要什么函数
                'only'=>['index','show']
            ]);
            /**
             * 分类管理
            */
            //分类的禁用/启用
            $api->patch('category/{category}/status',[\App\Http\Controllers\Admin\CategoryController::class,'status'])->name('category.status');
            //分类管理资源路由
            $api->resource('category',\App\Http\Controllers\Admin\CategoryController::class,[
                //除了什么以外的函数
                'except'=>['destroy']
            ]);

            /*
             * 商品管理
            */
            //是否上架
            $api->patch('goods/{good}/on',[\App\Http\Controllers\Admin\GoodsController::class,'isOn'])->name('goods.on');
            //是否推荐
            $api->patch('goods/{good}/recommend',[\App\Http\Controllers\Admin\GoodsController::class,'isRecommend'])->name('goods.recommend');
            //商品管理资源路由
            $api->resource('goods',\App\Http\Controllers\Admin\GoodsController::class,[
                //除了什么以外的函数
                'except'=>['destroy']
            ]);

            /**
             *评论管理
             */
            //评价列表
            $api->get('comments/',[\App\Http\Controllers\Admin\CommentController::class,'index'])->name('comments.index');
            //评价详情
            $api->get('comments/{comment}',[\App\Http\Controllers\Admin\CommentController::class,'show'])->name('comments.show');
            //商家回复
            $api->post('comments/{comment}/reply',[\App\Http\Controllers\Admin\CommentController::class,'reply'])->name('comments.reply');

            /**
             *订单管理
             */
            //订单列表
            $api->get('orders/',[\App\Http\Controllers\Admin\OrdersController::class,'index'])->name('orders.index');
            //订单详情
            $api->get('orders/{orders}',[\App\Http\Controllers\Admin\OrdersController::class,'show'])->name('orders.show');
            //订单状态
            $api->patch('orders/{orders}/post',[\App\Http\Controllers\Admin\OrdersController::class,'post'])->name('orders.post');

            /**
             * 轮播图管理
             */
            $api->patch('slides/{slide}/seq',[\App\Http\Controllers\Admin\SlideController::class,'seq'])->name('slides.seq');

            $api->patch('slides/{slide}/status',[\App\Http\Controllers\Admin\SlideController::class,'status'])->name('slides.status');
            $api->resource('slides',\App\Http\Controllers\Admin\SlideController::class);

            /**
             *菜单栏管理
             */
            $api->get('menus',[\App\Http\Controllers\Admin\MenuController::class,'index'])->name('menus.index');
        });
    });
});


