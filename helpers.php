<?php

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * 辅助函数->根目录创建文件->composer.json->autoload里配置
    "files": ["helpers.php"]->终端重新加载 composer dump-autoload
 */
//所有分类
if (!function_exists('categoryTree')){
    function categoryTree($group='goods',$status=false){
        //分类层级展示
        $category=Category::select(['id','pid','name','level','status'])
            ->when($status!==false,function ($query) use ($status){
                $query->where('status',$status);
            })
            ->where('group',$group)
            ->where('pid',0)
            ->with([
                'Children'=>function ($query) use ($status){
                    $query->select(['id','pid','name','level','status'])
                        ->when($status!==false,function ($query) use ($status){
                            $query->where('status',$status);
                        });
                    },
                'Children.Children'=>function ($query) use ($status){
                    $query->select(['id','pid','name','level','status'])
                        ->when($status!==false,function ($query) use ($status){
                        $query->where('status',$status);
                    });
                }
            ])
            ->get();
        return $category;
    }
}
//缓存没被禁用的商品分类
if (!function_exists('cache_category')){
    function cache_category(){
        return  cache()->rememberForever('cache_category',function (){
            return categoryTree('goods',1);
        });
    }
}

//缓存所有商品分类
if (!function_exists('cache_category_all')){
    function cache_category_all(){
       return cache()->rememberForever('cache_category_all',function (){
            return categoryTree('goods');
        });
    }
}

//缓存所有菜单栏分类
if (!function_exists('cache_category_menu_all')){
    function cache_category_menu_all(){
        return cache()->rememberForever('cache_category_menu_all',function (){
            return categoryTree('menu');
        });
    }
}
//缓存没被禁用的菜单栏分类
if (!function_exists('cache_category_menu')){
    function cache_category_menu(){
        return  cache()->rememberForever('cache_category_menu',function (){
            return categoryTree('menu',1);
        });
    }
}

//清空分类
if (!function_exists('forget_cache_category')){
    function forget_cache_category(){
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
        cache()->forget('cache_category_menu');
        cache()->forget('cache_category_menu_all');
    }
}

/**
 *邮箱验证码验证
 */
if(!function_exists('emailTree')){
    function emailTree($email,$code)
    {
        if (Cache::get('email_code'.$email) !=$code){
            return False;
        }else{
            return True;
        }
    }
}
    /**
     * 图片url处理
     */
    if (!function_exists('imgUrl')){
        function imgUrl($key){
        //如果没有$key
        if (empty($key)) return '';
        //如果key包含http等信息，是一个完整的地址，直接返回
        if(strpos($key,'http://')!==false
            ||strpos($key,'https://')!==false
            ||strpos($key,'data:image')!==false){
            return $key;
        }
        return env('STORANG_URL').$key;
    }
}


/**
 * 城市数据
 */
if (!function_exists('city_cache')){
    function city_cache($pid=0)
    {
        return cache()->rememberForever('city_cache'.$pid,function () use($pid){
            return \App\Models\City::where('pid',$pid)->get()
                ->keyBy('id');
        });
    }
}

/**
 * 通过3 ，4 ID 查询完整省市县信息
 */
if (!function_exists('city_name')){
    function city_name($city_id)
    {
        $city=\App\Models\City::where('id',$city_id)->with('parent.parent.parent.parent')->first();

        $arr=[
            $city['parent']['parent']['parent']['name']  ?? '',
            $city['parent']['parent']['name']  ?? '',
            $city['parent']['name']  ?? '',
            $city['name']  ?? '',
        ];

        return trim(implode(' ',$arr)) ;
    }
}



