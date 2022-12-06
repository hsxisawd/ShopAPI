<?php

namespace App\Transformers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    public function  transform(Comment $comment){
        $pics_url=[];
        if(is_array($comment->pics)){
            foreach ($comment->pics as $p){
                array_push($pics_url,env('STORANG_URL').$p) ;
            }
        }
        return[
            'id'=> $comment->id,
            'user_id'=> $comment->user_id,
            'goods_id'=> $comment->goods_id,
            'reply'=> $comment->reply,
            'content'=>$comment->content,
            'pics'=> $comment->pics,
            'pics_url'=>$pics_url,
            'rate'=>$comment->rate,
            "created_at"=> $comment->created_at,
            "updated_at"=> $comment->updated_at
        ];
}
    protected $availableIncludes=['user','goods'];
    public function includeUser(Comment $comment)
    {
        return $this->item($comment->user,new UserTransformer());
    }
    public function includeGoods(Comment $comment)
    {
        return $this->item($comment->goods,new GoodsTransformer());
    }
}
