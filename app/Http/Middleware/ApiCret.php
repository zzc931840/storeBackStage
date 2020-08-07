<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;

class ApiCret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $res=0;
       if(!$request->has('api_token')){
            $msg ='不存在';
            $res = 1;
        }
        $API =  $request->input('api_token');
         $arr = explode('-',$API);
         $user=Users::where('id',$arr[0])->get();
        if(empty($user[0])){
            $msg =$arr[0].'用户不存在';
           $res=1;
        }elseif($user[0]['api_token'] != $arr[1]){
            $msg ='token不对';
            $res=1;
        }

        if($res == 1){
            $res = array('status' => 404,'msg' => $msg);
            return response()->json($res);
        }
        $user_id=['user_id'=>$user[0]['id']];
        $request->merge($user_id);
        return $next($request);
    }
}
