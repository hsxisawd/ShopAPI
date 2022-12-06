<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
        /**
         * 分类列表
         */
        public function index(Request $request)
        {
            $type=$request->input('type','all');
            if($type=='all'){
                //调用辅助函数,查询所有的数据
                return cache_category_menu_all();
            }else{
                //调用辅助函数,查询没被禁用的数据
                return cache_category_menu();
            }
        }
}
