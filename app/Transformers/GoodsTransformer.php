<?php

namespace App\Transformers;

use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class GoodsTransformer extends TransformerAbstract
{
    public function  transform(Good $good){
        $pics_url=[];
        foreach ($good->pics as $p){
           array_push($pics_url,env('STORANG_URL').$p) ;
        }
        return[
            'id'=>$good->id,
            'title'=>$good->title,
            'category_id'=>$good->category_id,
            'description'=>$good->description,
            'price'=>$good->price,
            'stock'=>$good->stock,
            'cover'=>$good->cover,
            'cover_url'=>env('STORANG_URL').$good->cover,
            'pics'=>$good->pics,
            'pics_url'=>$pics_url,
            'details'=>$good->details,
            'is_on'=>$good->is_on,
            'is_recommend'=>$good->is_recommend,
            'created_at'=>$good->created_at,
            'updated_at'=>$good->updated_at,
        ];
        }
    protected $availableIncludes=['category','user','comments'];
    public function includeCategory(Good $good)
        {
            return $this->item($good->category,new CategoryTransformer());
        }
    public function includeUser(Good $good)
    {
        return $this->item($good->user,new UserTransformer());
    }
    public function includeComments(Good $good)
    {
        return $this->collection($good->comments,new CommentTransformer());
    }
}
