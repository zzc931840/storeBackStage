<?php
namespace App\Http\Controllers\Register;


use App\Users;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Tools\SMS;
use App\Http\Controllers\Controller;
use Mail;
use App\Jobs\QueueJob;
class register{

    public function index(Request $request ){
              $phone=$request->input('phone');
              $password=$request->input('password');
//              $code=$request->input('code');
//               $SmsPhone=SMS::get($phone);
               $ConJson = app()->make('ConJson');

             $API = md5($phone.$password);
             $user =  Users::where('name',$phone)->first();
                  if(empty($user)){
                   $data = [
                       'name' => $phone,
                       'password' => $password,
                       'api_token' => $API,
                       'status'=>0
                   ];
                      Users::create($data);
                  }
                  elseif($user['status'] == 0){
                       $user->password = $password;
                       $user->api_token = $API;
                       $user->save();
                  }
        $code = rand(0,1000).rand(0,1000).rand(0,1000).rand(0,1000);
               Redis::set($phone,$code);
//               if(Redis::get($SmsPhone) == $code) {
//                   $API = md5($phone.$password);
//                   $data = [
//                       'name' => $phone,
//                       'password' => $password,
//                       'api_token' => $API
//                   ];
//                   $ConJson->setAPI($API);
//                   if (Users::create($data)) {
//                       $ConJson->setStatus(200);
//                   } else {
//                       $ConJson->setStatus(401);
//                   }
//               }else{
//                   $ConJson->setStatus(402);
//                   $ConJson->setContent('验证码不正确');
//               }
//        Mail::raw("快来验证吧!!!http://localhost:8085/laravel/public/testing?email=$phone&code=$code",function ($message) use ($phone){
//            $message->subject('注册验证');
//            $message->to($phone);
//        });
       dispatch(new QueueJob($phone,$code));
        $ConJson->setStatus(200);
        return $ConJson->toJson();
    }

    public function testing(Request $request){
           $email = $request->input('email');
           $code = $request->input('code');
           if(Redis::get($email) == $code){
               $user =  Users::where('name',$email)->first();
               $user->status = 1;
               $user->save();
               echo '验证成功!快去登录吧!';
           }else{
               echo '非法入侵';
           }
    }
}

