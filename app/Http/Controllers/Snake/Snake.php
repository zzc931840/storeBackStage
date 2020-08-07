<?php

 namespace App\Http\Controllers\Snake;

 use App\Users;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Storage;
 use App\Snacks;
 class Snake{

       public  function SnakeUpload(Request $request){
           //获取用户id
            $user_id = $request->input('user_id');
            $price = $request->input('price');
            $SnackName = $request->input('TradeName');
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
            $Snacks = new Snacks();
            $Snacks->name = $SnackName;
            $Snacks->price =$price;
            $Snacks->img = $ImgSrc;
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
