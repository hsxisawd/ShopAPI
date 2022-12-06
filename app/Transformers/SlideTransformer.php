<?php

namespace App\Transformers;

use App\Models\Good;
use App\Models\Orders;
use App\Models\Slide;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class SlideTransformer extends TransformerAbstract
{
    public function  transform(Slide $slide){
        return[
            'id'=>$slide->id,
            'title'=>$slide->title,
            'img'=>$slide->img,
            'img_url'=>env('STORANG_URL').$slide->img,
            'url'=>$slide->url,
            'status'=>$slide->status,
            'seq'=>$slide->seq,
            'created_at'=>$slide->created_at,
            'updated_at'=>$slide->updated_at,
        ];
        }

}
