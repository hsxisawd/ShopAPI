<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;




class CategoryController extends BaseController
{
    /**
     * 分类列表
     */
    public function index(Request $request)
    {
        $type=$request->input('type','all');
        if($type=='all'){
            //调用辅助函数,查询所有的数据
            return cache_category_all();
        }else{
            //调用辅助函数,查询没被禁用的数据
            return cache_category();
        }


    }

    /**
        添加分类
     */
    public function store(Request $request)
    {
        $insertData=$this->checkInput($request);
        if (!is_array($insertData)) return $insertData;
        Category::create($insertData);

        return $this->response->created();
    }

    /**
     * 分类详情
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     *更新分类
     */
    public function update(Request $request,Category $category)
    {
        $request->offsetSet('id',$category->id);
        $upData=$this->checkInput($request);
        if (!is_array($upData)) return $upData;
        $category->update($upData);
        return $this->response->noContent();
    }

    //检查输入的参数
    protected function checkInput($request)
    {
        $request->validate([
            'name'=>'required|max:16'
        ],[
            'name.required'=>'分类名称不能为空'
        ]);
        $group=$request->input('group','goods');

        if (!empty($default_pid=$request->input('id'))){
            $pid=$request->input('pid',Category::find($default_pid)->pid);
        }else{
            $pid=$request->input('pid',0);
        }
        //分级计算
        $level=$pid==0?1:(Category::find($pid)->level+1);
        if($level>3){
            return $this->response->errorBadRequest('不能超过三级分类！');
        };
        return
            [
                'name'=>$request->input('name'),
                'pid'=>$pid,
                'level'=>$level,
                'group'=>$group
            ];
    }

    /**
     *
     */
    public function status(Category $category)
    {
        $category->status=$category->status==1?0:1;
        $category->save();
        return $this->response->noContent();
    }
}
