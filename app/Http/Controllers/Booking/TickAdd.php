<?php
namespace App\Http\Controllers\Booking;
use App\Ticks;
use Illuminate\Http\Request;
class TickAdd{
     public function index(Request $request){
         $Ticks = new Ticks();
         $ConJson = app()->make('ConJson');
         $Ticks->start = $request->input('start');
         $Ticks->end = $request->input('end');
         $Ticks->StartTime = $request->input('StartTime');
         $Ticks->EndTime = $request->input('EndTime');
         $Ticks->Price = $request->input('price');
         $Ticks->whether =0;
         if($Ticks->save()){
             $ConJson->setStatus(200);
         }else{
             $ConJson->setStatus(404);
         }
        return $ConJson->toJson();
     }
}
