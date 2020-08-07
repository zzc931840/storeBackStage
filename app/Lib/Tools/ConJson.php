<?php
namespace App\Lib\Tools;

class ConJson{

    protected $content=[];
    protected $api_token='';
    protected $status='';

    public function setContent($arr){
        $this->content=$arr;
    }

    public function setAPI($API){
        $this->api_token=$API;
    }

    public function setStatus($status){
        $this->status=$status;
    }
    public function toJson(){
        $arr=[];
        foreach ($this as $key=>$value){
            $arr[$key]=$value;
        }
        return json_encode($arr);
    }
}
