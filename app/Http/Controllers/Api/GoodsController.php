<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    /**
     * 商品列表
     */
    public  function index(Request $request)
    {
        //搜索条件
        $title=$request->query('title');
        $category_id=$request->query('category_id');

        //排序
        $sales=$request->query('sales');
        $price=$request->query('price');
        $comment_count=$request->query('comment');
        //商品分页数据
        $goods=Good::select('id','title','price','cover','category_id','sales')
            ->where('is_on',1)
            ->when($title,function ($query) use ($title){
                $query->where('title','like',"%{$title}%");
            })
            ->when($category_id,function ($query) use ($category_id){
                $query->where('category_id',$category_id);
            })
            ->when($sales==1,function ($query)use($sales) {
                $query->orderBy('sales','desc');
            })
            ->when($price==1,function ($query)use($price) {
                $query->orderBy('price','desc');
            })
            ->withCount('comments')
            ->when($comment_count==1,function ($query)use($comment_count) {
                $query->orderBy('comment_count','desc');
            })
            ->simplePaginate(20)
        ->appends([
            'title'=>$title,
            'category_id'=>$category_id,
            'price'=>$price,
            'sales'=>$sales,
            'comment_count'=>$comment_count
        ]);

        //推荐商品
        $recommend_goods=Good::select('id','title','price','cover')
            ->where('is_on',1)
            ->where('is_recommend',1)
            ->withCount('comments')
            ->inRandomOrder()
            ->take(10)
            ->get();
        //分类数据
        $category=cache_category();
        //返回数据
        return $this->response->array(
            [
                'goods'=>$goods,
                'recommend_goods'=>$recommend_goods,
                'category'=>$category
            ]
        );
    }



    /**
     * 商品详情
     */
    public  function  show($id)
    {
        //商品详情
        $goods=Good::where('id',$id)
            ->with([
                'comments.user'=>function ($query){
                $query->select('id','nickname','headimg');
                }
            ])
            ->first()->append('pics_url');

        //相似商品
        $like_goods=Good::where('is_on',1)
            ->select('id','title','price','cover','sales')
            ->where('category_id',$goods->category_id)
            ->inRandomOrder()
            ->take(10)
            ->get();


        //返回数据
        return $this->response->array(
            [
                'goods'=>$goods,
                'like_goods'=>$like_goods
            ]
        );
    }
}
