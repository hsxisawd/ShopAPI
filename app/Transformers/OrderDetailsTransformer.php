<?php

namespace App\Transformers;

use App\Models\Good;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrderDetailsTransformer extends TransformerAbstract
{
    public function  transform(OrdersDetails $ordersDetails){
        return[
            'id'=>$ordersDetails->id,
            'order_id'=>$ordersDetails->order_id,
            'goods_id'=>$ordersDetails->goods_id,
            'price'=>$ordersDetails->price,
            'num'=>$ordersDetails->num,
            'created_at'=>$ordersDetails->created_at,
            'updated_at'=>$ordersDetails->updated_at,
        ];
        }
    protected $availableIncludes=['goods'];


    public function includeGoods(OrdersDetails $ordersDetails)
    {
        return $this->item($ordersDetails->goods,new GoodsTransformer());
    }
}
