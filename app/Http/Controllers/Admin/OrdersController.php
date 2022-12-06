<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\OrderPost;
use App\Models\Orders;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrdersController extends BaseController
{
    /**
     * 订单列表
     */
    public function index(Request $request)
    {
        //查询条件
        $order_num=$request->input('order_num');
        $trade_num=$request->input('trade_num');
        $status=$request->input('status');
        $orders=Orders::when($order_num,function ($query) use($order_num){
            $query->where('order_num',$order_num);
        })->when($trade_num,function ($query) use($trade_num){
            $query->where('trade_num',$trade_num);
        })->when($status,function ($query) use($status){
            $query->where('status',$status);
        })
        ->paginate();
        return $this->response->paginator($orders,new OrderTransformer());
    }
    /**
     * 订单详情
     */
    public function show(Orders $orders)
    {
        return $this->response()->item($orders ,new OrderTransformer());
    }
    /**
     * 发货
     */
    public function post(Request $request,Orders $orders)
    {
        $request->validate([
            'express_type'=>'required|in:SF,YT,YD',
            'express_num'=>'required'
        ],[
            'express_type.required'=>'快递类型 必填',
            'express_type.in'=>'快递类型 只能是SF,YT,YD',
            'express_num.required'=>'快递单号 必填',
        ]);

        //分发事件
        event(new \App\Events\OrderPost(
            $orders,
            $request->input('express_type'),
            $request->input('express_num'),
        ));

        return $this->response->noContent();
    }
}
