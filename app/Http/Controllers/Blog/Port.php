<?php

 namespace App\Http\Controllers\Blog;

 use App\Users;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Storage;
 use App\userInfo;
 class Port{

       public  function PortUpload(Request $request){
           //获取用户id
            $user_id = $request->input('user_id');
            $user = Users::find($user_id);
            $username = $user->name;   //获取用户名
           //将base64转为图片
           $base64_img = $request->input('imgUrl');
           //替换编码头
           preg_match('/^(data:\s*image\/(\w+);base64,)/',$base64_img,$res);
           //获取后缀名
           preg_match('/(jpeg)|(jpg)|(png)|(gif)/',$base64_img,$data);
           $base64_img=base64_decode(str_replace($res[1],'', $base64_img));
           //获取文件后缀
           $fileName = 'data/'.$username.'/'.time().'.'.$data[0];
           Storage::disk('upload')->put($fileName,$base64_img);
           $ImgSrc =  env('WEB_URL').$fileName;
           $ConJson = app()->make('ConJson');
           $userInfo = userInfo::where('fid',$user_id)->get();
           if(empty($userInfo[0])){
               $Snacks = new userInfo();
           }else{
               $Snacks = $userInfo[0];
           }
           $Snacks->port= $ImgSrc;
           $Snacks->fid = $user_id;
           $Snacks->name = 0;
           $Snacks->save();

//            if () {
                $ConJson->setStatus(200);
                $ConJson->setContent($ImgSrc);
                return $ConJson->toJson();
//            }
//           $ConJson->setStatus(400);
//           $ConJson->setContent('upload error');
//           return $ConJson->toJson();

       }


 }
