<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'title',
        'category_id',
        'description',
        'price',
        'stock',
        'cover',
        'pics',
        'details'
    ];
    protected $casts=[
      'pics'=>'array'
    ];

    /**
     * 追加额外属性
     */
    protected $appends=['cover_url','pics_url'];

    /**
     * 处理url
     */
    public function getCoverUrlAttribute()
    {
        return imgUrl($this->cover);
    }
    public function getPicsUrlAttribute()
    {
        //使用集合
        return collect($this->pics)->map(function ($item){
            return imgUrl($item);
        });
    }
    /*
     * 商品所属的分类*/
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    /*
    * 商品所属的用户*/
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /** 商品所有的评价*/
    public function comments()
    {
        return $this->hasMany(Comment::class,'goods_id','id');
    }
}
