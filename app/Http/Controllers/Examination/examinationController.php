<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subject;
use App\Answer;
use App\anrecord;
use App\userRecord;
use App\TestPaper;
class examinationController extends Controller
{
    //
    public function index(){
       $data  = Subject::where('parId',1)->with('Answer')->get();
       $arr = [];
       $arr =$this->TwoChange($data);
       return json_encode($arr) ;
    }
    //进行比对答案
    public  function comparison(Request $request){
        $data  = Subject::where('parId',1)->get();
         $arr =$this->TwoChange($data);
         $test = $request->input('answer');
          $test = json_decode($test,true);
         $Set=0;

          //随机生成fmark
          $mark = md5(time()).rand().'zzc';
         foreach ($test as $key => $value){
                $strArr = explode('-',$key);
                if($strArr[0] == '多选题'){
                   $am = explode(',',$arr[$strArr[0]][$strArr[1]]['final']);
                   if(count($am) == count($value)){
                       $jude = true;
                        foreach ($value as $str){
                             if(!in_array($str,$am)){
                                 $jude = false;
                                 $Set = 0;
                                 break;
                             }
                        }
                        if($jude == true){
                            $Set = 1;
                        }
                   }else{
                       $Set = 0;
                   }
                }else {
                    if ($arr[$strArr[0]][$strArr[1]]['final'] == $value) {
                        $Set = 1;
                    }else{
                        $Set = 0;
                    }
                }
                if($Set == 0){
                    $jude ='错误的';
                }else{
                    $jude = '正确的';
                }
             $anrecord =  new anrecord();
             $anrecord->fid = $arr[$strArr[0]][$strArr[1]]['id'];
             $anrecord->fmark = $mark;
             $anrecord->jude = $jude;
             if(is_array($value)){
                 $valueStr ='';
                 for ($i=0;$i<count($value);$i++){
                      if($i == count($value)-1){
                           $valueStr.=$value[$i];
                      }else{
                          $valueStr.=$value[$i].',';
                      }
                 }
                 $anrecord->content = $valueStr;
             }else {
                 $anrecord->content = $value;
             }
             $anrecord->save();
         }
         $userRecord =  new userRecord();
         $userRecord->userId = 11;
         $userRecord->paperId = 1;
         $userRecord->score = 0;
         $userRecord->mark = $mark;
         $userRecord->save();
         return json_encode($mark);
    }

    //处理试卷结果
    public  function  ExResult(Request $request){
          $mark = $request->input('mark');
          $userRecord = userRecord::where('mark',$mark)->get();
          $subject  = Subject::where('parId',$userRecord[0]->paperId)->with('Answer')->get();
          $AnRecord =anrecord::where('fmark',$mark)->get();
          $subject= $subject->toArray();
          foreach ($subject as $item=>$value){
                foreach ($AnRecord as $Record){
                      if($value['id'] == $Record['fid']){
                          $subject[$item]['userAnswer'][]= $Record->toArray();
                      }
                }
          }
          $subject = $this->TwoChange($subject);
          return json_encode($subject);

    }

    //处理数组结构
    public function TwoChange($data){
        $arr = [];
        foreach($data as $value){
            $arr[$value['type']][]=$value;
        }
        return $arr;
    }

    //获取试卷信息
    public function getExamination(){
         $TestPaper =  new TestPaper();
         $TestPaper = $TestPaper->get();
         return json_encode($TestPaper);
    }
}
