<?php

namespace App\Transformers;

use App\Models\Good;
use App\Models\Orders;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function  transform(Orders $orders){
        return[
            'id'=>$orders->id,
            'order_num'=>$orders->order_num,
            'user_id'=>$orders->user_id,
            'amount'=>$orders->amount,
            'status'=>$orders->status,
            'address_id'=>$orders->address_id,
            'express_type'=>$orders->express_type,
            'express_num'=>$orders->express_num,
            'pay_time'=>$orders->pay_time,
            'pay_type'=>$orders->pay_type,
            'trade_num'=>$orders->trade_num,
            'created_at'=>$orders->created_at,
            'updated_at'=>$orders->updated_at,
        ];
        }
    protected $availableIncludes=['user','orderDetails','goods'];

    public function includeUser(Orders $orders)
    {
        return $this->item($orders->user,new UserTransformer());
    }
    public function includeGoods(Orders $orders)
    {
        return $this->collection($orders->goods,new GoodsTransformer());
    }
    public function includeOrderDetails(Orders $orders)
    {
        return $this->collection($orders->orderDetails,new OrderDetailsTransformer());
    }
}
