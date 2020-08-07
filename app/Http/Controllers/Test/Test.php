<?php
namespace App\Http\Controllers\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Demo\SendTemplateSMS;
use Illuminate\Support\Facades\Redis;
use App\Ticks;
class Test{
     public function index(Request $request){
         $start=time()+4000;
         $end=$start+3000;
         $Ticks = new Ticks();
         $Ticks->start = '上海';
         $Ticks->end = '北京';
         $Ticks->StartTime = $start;
         $Ticks->EndTime = $end;
         $Ticks->Price = 270;
         $Ticks->whether =0;
         $Ticks->save();
     }
}
