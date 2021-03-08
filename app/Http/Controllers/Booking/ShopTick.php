<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\shop_Tick;

class ShopTick extends Controller
{
    function ConJson(){
        return app()->make('ConJson');
    }
    function  index(Request $request){

           $userId = $request->input('user_id');
           $TickId = $request->input('tick_id');
           $seat = $request->input('seat');
           $price = $request->input('price');
           $order = md5(rand(0,100)+rand(0,200)+time());
           $ConJson= $this->ConJson();
          $shop_Tick = new shop_Tick();
          $shop_Tick->user_id=$userId;
          $shop_Tick->tick_id=$TickId;
          $shop_Tick->seat=$seat;
          $shop_Tick->price=$price;
          $shop_Tick->order=$order;
          if ($shop_Tick->save()){
              $ConJson->setContent($order);
              $ConJson->setStatus(200);
          }else{
              $ConJson->setContent('error');
              $ConJson->setStatus(404);
          }
           return $ConJson->toJson();
    }
    function get(Request $request){
             $id=$request->input('tick_id');
             $ConJson= $this->ConJson();
             $arr = shop_Tick::where('tick_id',$id)->get();
             if(empty($arr[0])){
                   $ConJson->SetContent($id);
                   $ConJson->Setstatus(400);
             }else{
                 $seatArr= [];
                   foreach ($arr as $value){
                         $seat= explode(',',$value->seat);
                         foreach ($seat as $str){
                              $seatArr[] = $str;
                         }
                   }
                   $ConJson->SetContent($seatArr);
                  $ConJson->Setstatus(200);
             }
             return $ConJson->toJson();
    }
}
