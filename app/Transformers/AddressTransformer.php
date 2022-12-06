<?php

namespace App\Transformers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{
    public function  transform(Address $address){

        return[
            'id'=>$address->id,
            'city_id'=>$address->city_id,
            'city_name'=>city_name($address->city_id),
            'address'=>$address->address,
            'name'=>$address->name,
            'phone'=>$address->phone,
            'created_at'=>$address->created_at,
            'updated_at'=>$address->updated_at,
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
