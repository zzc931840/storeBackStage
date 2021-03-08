<?php
namespace App\Http\Controllers\Booking;
use App\Ticks;
use Illuminate\Http\Request;
use App\Users;

use Illuminate\Support\Facades\DB;
class booking{
      public function index(Request $request){
         $start= $request->input('start');
         $end= $request->input('end');
         $startTime = $request->input('startTime');
         $endTime= $request->input('endTime');
          $arr= Ticks::where(function($query) use($start,$end,$startTime,$endTime){
                $query->where('StartTime','>',time())->where('StartTime','>',$startTime)->where('EndTime','<',$endTime)->where('start','=',$start)->where('end','=',$end);
          })->get();
          $ConJson=app()->make('ConJson');
          $ConJson->setContent($arr);
          $ConJson->setStatus(200);
          return $ConJson->toJson();
      }

      public function userSelect(Request $request){
           $user_id =  $request->input('user_id');
           $userData = Users::find($user_id)->with('UserTick')->get();
           unset($userData[0]['password']);
          foreach ($userData[0]->UserTick as &$value){
               $arr=explode(',', $value->pivot->seat);
               $value->num=count($arr);
          }
          $ConJson=app()->make('ConJson');
          $ConJson->setStatus(200);
          $ConJson->setContent($userData->toJson());
          return $ConJson->toJson();
      }
}
