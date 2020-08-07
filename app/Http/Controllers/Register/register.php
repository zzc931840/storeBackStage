<?php
namespace App\Http\Controllers\Register;


use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Tools\SMS;

class register{

    public function index(Request $request ){
              $phone=$request->input('phone');
              $password=$request->input('password');
              $code=$request->input('code');
               $SmsPhone=SMS::get($phone);
               $ConJson = app()->make('ConJson');
               if(Redis::get($SmsPhone) == $code) {
                   $API = md5($phone.$password);
                   $data = [
                       'name' => $phone,
                       'password' => $password,
                       'api_token' => $API
                   ];
                   $ConJson->setAPI($API);
                   if (Users::create($data)) {
                       $ConJson->setStatus(200);
                   } else {
                       $ConJson->setStatus(401);
                   }
               }else{
                   $ConJson->setStatus(402);
                   $ConJson->setContent('验证码不正确');
               }
        return $ConJson->toJson();
    }
}

