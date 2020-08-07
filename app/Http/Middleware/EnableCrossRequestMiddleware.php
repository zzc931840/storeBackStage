<?php

namespace App\Http\Middleware;

use Closure;

class EnableCrossRequestMiddleware
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
//        $respone= $next($request);
//        $allow_origin = [
//                        'http://localhost:8085',
//        ];
//       $respone->header('Content-Type: text/html;charset=utf-8',$allow_origin);
//        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Allow-Methods:POST,GET,PUT,OPTIONS,DELETE'); // 允许请求的类型
//        header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
//        header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Origin,Access-token,Content-Length,Accept-Encoding,X-Requested-with, Origin,Access-Control-Allow-Methods'); // 设置允许自定义请求头的字段
//        return  $respone;
    }
}
