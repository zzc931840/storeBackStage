<?php
namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Users;
class login {
    function index(Request $request){
           $name= $request->input('name');
           $password= $request->input('password');
           $user=Users::where('name',$name)->get();
           $ConJson=app()->make('ConJson');
           if(isset($user[0])){
               if ($user[0]['password'] == $password && $user[0]['status'] == 1) {
                     $ConJson->setStatus(201);
                     $ConJson->setContent('Login Success!');
                     $API = md5($name.$password.time());
                     $user = Users::find($user[0]['id']);
                     $userId = $user['id'];
                     $user->api_token=$API;
                     $user->save();
                     $API = $userId.'-'.$API;
                     $ConJson->setAPI($API);
               }
           }
           if(empty($API) || !isset($API)){
               $ConJson->setStatus(400);
               $ConJson->setContent('name or password error!');
           }
           return $ConJson->toJson();
    }
}
