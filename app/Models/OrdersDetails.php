<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    use HasFactory;

    protected $fillable=['price','num','order_id','goods_id'];

    public function order()
    {
        return $this->belongsTo(Orders::class,'order_id','id');
    }
    public function goods()
    {
        return $this->hasOne(Good::class,'id','goods_id');
    }
}
