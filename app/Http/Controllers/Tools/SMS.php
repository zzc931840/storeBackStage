<?php
namespace App\Http\Controllers\Tools;

class SMS{
    public static function get($phone){
         return 'SMS'.$phone;
    }
}
