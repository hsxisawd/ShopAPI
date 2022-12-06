<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Good;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /**
     * 订单列表
     */
    public function  index(Request $request)
    {
        //按商品订单状态搜索
        $status=$request->query('status');
        //按商品名称搜索
        $title=$request->query('title');

        $orders=Orders::where('user_id',auth('api')->id())
            ->when($status,function ($query) use ($status){
                $query->where('status',$status);
            })
            ->when($title,function ($query) use ($title){
                $query->whereHas('goods',function ($query) use ($title){
                    $query->where('title','like',"%{$title}%");
                });
            })
            ->paginate(3);

        return $this->response->paginator($orders,new OrderTransformer());
    }

    /**
     * 预览订单
     */
    public function preview()
    {
        //地址数据
        $address=Address::where('user_id',auth('api')->id())
            ->orderBy('is_default','desc')
            ->get();
        //购物车数据
        $carts=Cart::where('user_id',auth('api')->id())
                ->where('is_checked',1)
                ->with('goods:id,cover,title,price')
                ->get();

        //返回数据
        return $this->response->array([
            'address'=>$address,
            'carts'=>$carts
        ]);
    }

    /**
     * 提交订单
     */
    public  function store(Request $request)
    {
        $request->validate([
            'address_id'=>'required|exists:addresses,id'
        ],[
            'address_id.required'=>[
                '收货地址 不能为空'
            ]
        ]);

        //处理插入数据
        $user_id=auth('api')->id();
        $order_num=date('YmdHis').rand(100000,999999);
        $amount=0;

        $cartsQuery=Cart::where('user_id',$user_id)
            ->where('is_checked',1)
            ->with('goods:id,price,stock');

        $carts=$cartsQuery->get();
        // 要插入订单详情的数据
        $insertData=[];
        if(empty($cartsQuery->first())) return $this->response->errorBadRequest('购物车为空 请添加商品');

        foreach ($carts as $key=>$cart) {
            //如果有商品库存量不足，提示用户
            if($cart->goods->stock < $cart->num){
                return  $this->response->errorBadRequest($cart->goods->title.' 库存不足，请重新选择数量');
            }


            $insertData[]=[
                'goods_id'=>$cart->goods_id,
                'price'=>$cart->goods->price,
                'num'=>$cart->num
            ];

            $amount+=$cart->goods->price * $cart->num;
        }
        try {
            //开启数据库事务管理
            DB::beginTransaction();
            // 生成订单
            $order=Orders::create([
                'user_id' => $user_id,
                'order_num' => $order_num,
                'address_id' => $request->input('address_id'),
                'amount' => $amount
            ]);
            //生成订单详情
            $order->orderDetails()->createMany($insertData);

            //删除已经结算的购物车数据
            $cartsQuery->delete();

            //减去商品库存量
            foreach ($carts as $cart){
                Good::where('id',$cart->goods_id)->decrement('stock',$cart->num);
            }


            //事务提交
            DB::commit();
            return $this->response->created();

        }catch (\Exception $e){
            //出现错误，进行数据回滚
            DB::rollBack();
            throw $e;
        }
    }
    //确认收货
    public  function  confirm(Orders $orders)
    {
        if ($orders->status!=3){
            return $this->response->errorBadRequest('订单状态异常');
        }
        try{
            DB::beginTransaction();

            $orders->status=4;
            $orders->save();

            $ordersDetails=$orders->orderDetails;
            //增加订单销量
            foreach ($ordersDetails as $ordersDetail){
                //更新商品销量
                Good::where('id',$ordersDetail->goods_id)->increment('sales',$ordersDetail->num);
            }

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }

        return  $this->response->noContent();
    }
}

