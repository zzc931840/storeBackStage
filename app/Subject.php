<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    protected $table = 'subject';

    public function Answer(){
         return $this->hasMany('App\Answer','subjectId','id');
    }
}
