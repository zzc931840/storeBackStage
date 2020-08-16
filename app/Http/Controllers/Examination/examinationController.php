<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subject;
use App\Answer;
use App\anrecord;
use App\userRecord;
use App\TestPaper;
use Illuminate\Support\Facades\DB;

class examinationController extends Controller
{
    //
    public function index(Request $request){
        $paperId = $request->input('paperId');
       $data  = Subject::where('parId',$paperId)->with('Answer')->get();
       $arr = [];
       $arr =$this->TwoChange($data);
       return json_encode($arr) ;
    }
    //进行比对答案
    public  function comparison(Request $request){
        $userId = $request->input('user_id');
        $data  = Subject::where('parId',1)->get();
         $arr =$this->TwoChange($data);
         $test = $request->input('answer');
          $test = json_decode($test,true);
          $score = 0;
          //随机生成fmark
          $mark = md5(time()).rand().'zzc';
         foreach ($test as $key => $value){
                $Set=0;
                $strArr = explode('-',$key);
                if($strArr[0] == '多选题'){
                   $am = explode(',',$arr[$strArr[0]][$strArr[1]]['final']);
                   if(count($am) == count($value)){
                       $jude = true;
                        foreach ($value as $str){
                             if(!in_array($str,$am)){
                                 $jude = false;
                                 break;
                             }
                        }
                        if($jude == true){
                            $Set = 1;
                        }
                   }
                }else {
                    if ($arr[$strArr[0]][$strArr[1]]['final'] == $value) {
                        $Set = 1;
                    }
                }
                if($Set == 0){
                    $jude ='错误的';
                }else{
                    $jude = '正确的';
                   $score +=$arr[$strArr[0]][$strArr[1]]['score'];
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
         $userRecord = userRecord::where(['userId'=>8,'paperId'=>1])->get();
         if(empty($userRecord[0])){
             $userRecord =  new userRecord();
             $userRecord->userId = $userId;
             $userRecord->paperId = 1;
             $userRecord->score = $score;
             $userRecord->mark = $mark;
             $userRecord->save();
         }else{
             $userRecord[0]->userId = $userId;
             $userRecord[0]->paperId = 1;
             $userRecord[0]->score = $score;
             $userRecord[0]->mark = $mark;
             $userRecord[0]->save();
         }
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
        $TestPaper =  DB::select('select (select sum(score) from subject WHERE subject.parId = test_paper.id) as score, name , id  from test_paper');
//         $TestPaper =  new TestPaper();
//         $TestPaper = $TestPaper->get();
         return json_encode($TestPaper);
    }
}
