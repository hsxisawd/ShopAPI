<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空缓存
        app()['cache']->forget('spatie.permission.cache');
        //添加权限
        $permission=[
            ['name'=>'user.index','cn_name'=>'用户列表','guard_name'=>'api'],
            ['name'=>'user.show','cn_name'=>'用户详情','guard_name'=>'api'],
            ['name'=>'user.lock','cn_name'=>'用户启用禁用','guard_name'=>'api'],
            ['name'=>'category.status','cn_name'=>'分类禁用启用','guard_name'=>'api'],
            ['name'=>'category.index','cn_name'=>'分类列表','guard_name'=>'api'],
            ['name'=>'category.show','cn_name'=>'分类详情','guard_name'=>'api'],
            ['name'=>'category.update','cn_name'=>'分类更新','guard_name'=>'api'],
            ['name'=>'category.store','cn_name'=>'分类添加','guard_name'=>'api'],
            ['name'=>'goods.on','cn_name'=>'商品上架下架','guard_name'=>'api'],
            ['name'=>'goods.recommend','cn_name'=>'商品推荐不推荐','guard_name'=>'api'],
            ['name'=>'goods.index','cn_name'=>'商品列表','guard_name'=>'api'],
            ['name'=>'goods.show','cn_name'=>'商品详情','guard_name'=>'api'],
            ['name'=>'goods.update','cn_name'=>'商品更新','guard_name'=>'api'],
            ['name'=>'goods.store','cn_name'=>'商品添加','guard_name'=>'api'],
            ['name'=>'comments.index','cn_name'=>'评价列表','guard_name'=>'api'],
            ['name'=>'comments.show','cn_name'=>'评价详情','guard_name'=>'api'],
            ['name'=>'comments.reply','cn_name'=>'商家回复','guard_name'=>'api'],
            ['name'=>'orders.index','cn_name'=>'订单列表','guard_name'=>'api'],
            ['name'=>'orders.show','cn_name'=>'订单详情','guard_name'=>'api'],
            ['name'=>'orders.post','cn_name'=>'订单发货','guard_name'=>'api'],
            ['name'=>'slides.index','cn_name'=>'轮播图列表','guard_name'=>'api'],
            ['name'=>'slides.show','cn_name'=>'轮播图详情','guard_name'=>'api'],
            ['name'=>'slides.update','cn_name'=>'轮播图更新','guard_name'=>'api'],
            ['name'=>'slides.store','cn_name'=>'轮播图添加','guard_name'=>'api'],
            ['name'=>'slides.seq','cn_name'=>'轮播图排序','guard_name'=>'api'],
            ['name'=>'slides.status','cn_name'=>'轮播图状态启用禁用','guard_name'=>'api'],
            ['name'=>'menus.index','cn_name'=>'菜单栏列表','guard_name'=>'api'],
        ];
        foreach ($permission as $value){
            Permission::create($value);
        }

        //添加角色
        $role=Role::create(['name'=>'super-admin','cn_name'=>'超级管理员','guard_name'=>'api']);

        //为角色添加权限
        $role->givePermissionTo(Permission::all());
    }
}
