<?php

namespace App\Transformers;

use App\Models\Cart;
use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    public function  transform(Cart $cart){

        return[
            'id'=>$cart->id,
            'user_id'=>$cart->user_id,
            'goods_id'=>$cart->goods_id,
            'num'=>$cart->num,
            'is_checked'=>$cart->is_checked,
            'created_at'=>$cart->created_at,
            'updated_at'=>$cart->updated_at,
        ];
        }
        /**
         * 额外的商品数据
         */
        protected $availableIncludes=['goods'];
        public function includeGoods(Cart $cart)
        {
            return $this->item($cart->goods,new GoodsTransformer());
        }
}
