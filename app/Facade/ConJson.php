<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ConJson extends Facade {

      protected static function getFacadeAccessor()
      {
         return  'ConJson';
      }
}
