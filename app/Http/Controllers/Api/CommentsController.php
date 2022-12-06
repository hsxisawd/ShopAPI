<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Orders;
use Illuminate\Http\Request;

class CommentsController extends BaseController
{
    public  function store(Request $request ,Orders $orders)
    {
        $request->validate([
            'goods_id'=>'required',
            'content'=>'required'
        ],[
            'goods_id.required'=>'商品id 不能为空',
            'content.required'=>'评论内容 不能为空'
        ]);

        // 确认收货才能评论 status=4
        if ($orders->status!=4){
            return $this->response->errorBadRequest('订单状态异常');
        }
        //要评论的商品必须是这个订单里面

        if(!in_array($request->input('goods_id'), $orders->orderDetails()->pluck('goods_id')->toArray())){
            return  $this->response->errorBadRequest('此订单不包含该商品');
        }

        //已经评论过的不能评论
        $checkComment=Comment::where('user_id',auth('api')->id())
            ->where('order_id',$orders->id)
            ->where('goods_id',$request->input('goods_id'))
            ->count();
        if($checkComment>0){
            return  $this->response->errorBadRequest('此商品已经评论过了');
        }

        // 生成评论数据
        $request->offsetSet('user_id',auth('api')->id());
        $request->offsetSet('order_id',$orders->id);

        Comment::create($request->all());

        return $this->response->noContent();
    }
}
