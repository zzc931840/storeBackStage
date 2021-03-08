<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Tools\Factory;
use Illuminate\Support\Facades\DB;

class Comment extends Controller
{
    //
    public function index(Request $request){
              $uid = $request->input('user_id');
              $hid = $request->input('hid');
              $did = $request->input('did');
              $lid = $request->input('lid');
              $pid = $request->input('pid');
              $content = $request->input('content');
             $comment =  Factory::Get('Comment');
             if($hid ==0){
                 $lid = $comment->where('did', $did)->where('hid', 0)->count();
                 $lid = $lid + 1;
             }
             $comment = Factory::Get('Comment');
             $comment->lid = $lid;
             $comment->pid = $pid;
             $comment->uid = $uid;
             $comment->hid = $hid;
             $comment->did =$did;
             $comment->content = $content;
             $comment->save();
             return json_encode(['status'=>200]);
    }

    public function Handel(Request $request){
            $id = $request->input('id');
          $data =   DB::select("SELECT (select name from users where id = comment.uid) as uname,(select name from users where id = comment.hid) as hname,(select port from userinfo where fid = comment.uid) as port,uid,hid,lid,content,id,pid FROM `comment` WHERE did = $id ");

          $arr = [];

         foreach ($data as $value){
              if($value->hid == 0){
                  $arr[$value->lid] = $value;
              }
         }

         foreach ($data as $value){
             if($value->hid !=0){
                 if(isset($arr[$value->lid]->child)){
                     $arr[$value->lid]->child[]=$value;
                 }else{
                     $arr[$value->lid]->child = [];
                     $arr[$value->lid]->child[]=$value;
                 }
             }
         }
         return json_encode(['data'=>$arr,'status']);
    }


}
