<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SlideRequest;
use App\Models\Slide;
use App\Transformers\SlideTransformer;
use Illuminate\Http\Request;

class SlideController extends BaseController
{
    /**
     * 轮播图列表
     */
    public function index()
    {
        $slide=Slide::paginate(2);

        return $this->response->paginator($slide,new SlideTransformer());
    }

    /**
     * 轮播图添加
     */
    public function store(SlideRequest $request)
    {
        //查询最大的seq
        $max_seq=Slide::max('seq')??0;
        $max_seq++;
        $request->offsetSet('seq',$max_seq);
        Slide::create($request->all());

        return $this->response()->created();
    }

    /**
     * 轮播图详情
     */
    public function show(Slide $slide)
    {
        return $this->response->item($slide , new SlideTransformer());
    }

    /**
     * 轮播图更新
     */
    public function update(SlideRequest $request, Slide $slide)
    {

        $slide->update($request->all());
        return $this->response->noContent() ;

    }

    /**
     * 轮播图删除
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        return $this->response->noContent() ;
    }

    /**
     * 排序
     */
    public function seq(Request $request,Slide $slide)
    {
        $slide->seq=$request->input('seq',1);
        $slide->save();
        return $this->response->noContent() ;
    }
    /**
     * 轮播图禁用启用
     */
    public function status(Slide $slide)
    {
        $slide->status=$slide->status==1?0:1;
        $slide->save();
        return $this->response->noContent() ;
    }
}
