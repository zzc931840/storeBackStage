<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestPaper extends Model
{
    //
    protected $table = 'test_paper';
    protected $fillable= ['name'];

    public function SelectType(){
        return $this->hasMany('App\PaperType','paperId','id');
    }
}
