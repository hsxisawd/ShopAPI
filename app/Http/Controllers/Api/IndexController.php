<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Good;
use App\Models\Slide;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index(){
        //轮播图数据
        $sliders=Slide::where('status',1)
            ->orderBy('seq')
            ->get();
        //分类数据
        $category=cache_category();
        //商品数据
        $goods=Good::where('is_on',1)
            ->where('is_recommend',1)
            ->take(20)
            ->get();

        return $this->response->array(
            [
                'slides'=>$sliders,
                'category'=>$category,
                'goods'=>$goods,
            ]
        );
    }
}
