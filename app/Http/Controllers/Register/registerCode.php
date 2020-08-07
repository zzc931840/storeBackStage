<?php
namespace App\Http\Controllers\Register;
use Illuminate\Http\Request;
use App\Http\Controllers\Demo\SendTemplateSMS;
use App\Http\Controllers\Tools\SMS;
use Illuminate\Support\Facades\Redis;
class registerCode {
    public function index(Request $request){
          $phone = $request->input('phone');
        $ivrDial = new SendTemplateSMS();
         $rand=0;
         for ($i=0;$i<4;$i++){
             $rand .= rand(1,10);
         }
        $data=$ivrDial->sendTemplateSMS($phone,[$rand,10],1);
        $ConJson = app()->make('ConJson');
         if($data['status'] == 3) {
             $phone = SMS::get(19856839676);
             Redis::set($phone,$rand,'EX',600);
             $ConJson->setStatus(200);
         }else{
             $ConJson->setStatus(400);
         }
             $ConJson->setContent($data['content']);
              return $ConJson->toJson();
    }
}
