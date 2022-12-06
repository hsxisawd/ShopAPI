<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable=['user_id','order_num','amount','address_id'];
    public  function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public  function orderDetails(){
        return $this->hasMany(OrdersDetails::class,'order_id','id');
    }

    /**
     * 关联表远程一对多，连接goods表
     */
    public function goods()
    {
        return $this->hasManyThrough(
            Good::class,//最终关联的模型
            OrdersDetails::class,//中间模型
            'order_id',//中间模型和本模型关联的外键
            'id',// 最终关联模型的外键
            'id',//本模型和中间模型关联的键
            'goods_id'//中间表和最终模型关联的一个键
        );
    }
}
