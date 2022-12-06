<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class TestController extends BaseController
{
    public function index(){
//        单条数据的响应器
//        $user =User::find(1);
//        return $this->response->item($user,new UserTransformer());
//        token使用
        $users=User::all();
        return $this->response->collection($users,new UserTransformer());
    }
//    登录
    public function login(Request $request){

        $credentials=request(['email','password']);
        if(!$token=auth('api')->attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return  $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
       return response() ->json([
           'access_token'=>$token,
           'token_type'=> 'Bearer',
           'expires_in'=>auth('api')->factory()->getTTL()*60
       ]);
    }
}
