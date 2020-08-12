<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    protected $table = 'users';
    protected $fillable= ['name','password','api_token','status'];

    public function UserTick(){
        return $this->belongsToMany('App\Ticks','App\shop_Tick','user_id','tick_id')->withPivot('seat','price')->orderBy('id','desc');
    }

}
