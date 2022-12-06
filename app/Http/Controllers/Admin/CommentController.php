<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Good;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /**
     * 评论列表
     */
    public function index(Request $request)
    {
        //获取搜索条件
        $rate=$request->query('rate');
        $goods_title=$request->query('goods_title');

        $comments=Comment::when($rate,function ($query) use($rate){
            $query->where('rate',$rate);
        })
        ->when($goods_title,function ($query) use($goods_title) {
            $goods_ids=Good::where('title','like',"%{$goods_title}%")->pluck('id');
            $query->whereIn('goods_id',$goods_ids);
        })
        ->paginate(1);
        return $this->response->paginator($comments,new CommentTransformer());
    }


    /**
     * 评论详情
     */
    public function show(Comment $comment)
    {
        return $this->response()->item($comment ,new CommentTransformer());
    }

    /**
     *商家回复
     */
    public function reply(Request $request,Comment $comment)
    {
        $request->validate([
            'reply'=>'required|max:255'
        ],[
            'reply.required'=>'回复 不能为空',
            'reply.max'=>'回复 不能超过255个字符'
        ]);

        $comment->reply=$request->input('reply');
        $comment->save();
        return $this->response->noContent();
    }
}
