<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Tools\Factory;

class Browse extends Controller
{
    //
    public function index(Request $request){
            $id = $request->input('id');
             $ip = $_SERVER["REMOTE_ADDR"];
            $browse = Factory::Get('Browse')->where('ip',$ip)->where('did',$id)->get();
            if(empty($browse[0])){
                $browse =  Factory::Get('Browse');
                $browse->ip = $ip;
                $browse->did = $id;
                $browse->save();
            }
            return  json_encode(['status'=>200]);
    }
    public function getBrowse(Request $request){
              $id = $request->input('id');
              $browse = Factory::Get('Browse');
             $count = $browse->where('did',$id)->count();
             return json_encode(['status'=>200,'count'=>$count]);
    }
    public function Top(){
         $blog = Factory::Get('BlogContent');
         $data  =  $blog->withCount('GetBrowse')->with('GetDirectory')->limit(5)->orderBy('get_browse_count','desc')->get();
         return json_encode(['status'=>200,'data'=>$data]);
    }
}
