<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;

use App\Http\Requests\Api\AddressRequest;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     * 地址列表
     */
    public function index()
    {
        $address=Address::where('user_id',auth('api')->id())->get();
        return $this->response()->collection($address,new AddressTransformer());
    }

    /**
     * 添加地址
     */
    public function store(AddressRequest $request)
    {
        $user_id=auth('api')->id();
        $request->offsetSet('user_id',$user_id);
        $address=Address::where('user_id',$user_id)->first();
        if (!$address){
            $request->offsetSet('is_default',1);
        }
        Address::create($request->all());
        return $this->response()->created();
    }

    /**
     * 地址详情
     */
    public function show(Address $address)
    {
        return $this->response->item($address,new AddressTransformer());
    }

    /**
     * 更新地址
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->all());
        return $this->response->noContent();
    }

    /**
     *  删除地址
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return $this->response->noContent();
    }

    /**
     * 是否设置为默认
     */
    public function default(Address $address)
    {
        if ($address->is_default==1){
            return $this->response->noContent();
        }
        try {
            DB::beginTransaction();
            //先把所有地址设置为非默认
            $default_address=Address::where('user_id',auth('api')->id())
                ->where('is_default',1)
                ->first();

            if(!empty($default_address)){
                $default_address->is_default=0;
                $default_address->save();
            }
            //在设置为当前的设置为默认
            $address->is_default=1;
            $address->save();
            DB::commit();
            return $this->response->noContent();

        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

}
