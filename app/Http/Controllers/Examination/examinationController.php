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
use App\PaperType;

class examinationController extends Controller
{
    //
    public $arr =[];

    public function index(Request $request){

        $paperId = $request->input('paperId');
        $paperType = PaperType::where('paperId',$paperId)->get();
        $list=[];
        foreach ($paperType as $key=>$value){
            $subSet = Subject::where([
                  ['type',$value['typeName']],
                  ['parId',$paperId]
              ])->with('Answer')->orderByRaw("RAND()")->limit($value['topicNum'])->get()->toArray();
//            dd($subSet);
          $list = array_merge($list,$subSet);
        }
//       $data  = Subject::where('parId',$paperId)->with('Answer')->get();
       $arr = [];
       $arr =$this->TwoChange($list);
       return json_encode($arr) ;
    }
    //进行比对答案
    public  function comparison(Request $request){
        $userId = $request->input('user_id');
        $parId= $request->input('parId');
//        $data  = Subject::where('parId',$parId)->get();
        $test = $request->input('answer');
        $test = json_decode($test,true);
        $arrIdSet=[];
        foreach ($test as $index => $item){
            $mo = explode('-',$index);
            $arrIdSet[] = $mo[1];
        }
         $data = Subject::whereIn('id',$arrIdSet)->get();
         $arr =$this->TwoChange($data);
         $mut=[];
          foreach ($arr as $name=>$lg){
                foreach ($lg as $kel){
                    $mut[$name][$kel['id']] = $kel;
                }
          }
          $arr = $mut;
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
//                    dd($arr[$strArr[0]]);
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
         $userRecord = userRecord::where(['userId'=>$userId,'paperId'=>$parId])->get();
         if(empty($userRecord[0])){
             $userRecord =  new userRecord();
             $userRecord->userId = $userId;
             $userRecord->paperId = $parId;
             $userRecord->score = $score;
             $userRecord->mark = $mark;
             $userRecord->save();
         }else{
             $userRecord[0]->userId = $userId;
             $userRecord[0]->paperId = $parId;
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
          $AnRecord =anrecord::where('fmark',$mark)->get();
          $IdSet =[];
          foreach ($AnRecord as $value){
              $IdSet[] = $value['fid'];
          }
          $subject  = Subject::where('parId',$userRecord[0]->paperId)->whereIn('id',$IdSet)->with('Answer')->get();

          $subject= $subject->toArray();
          foreach ($subject as $item=>$value){
                foreach ($AnRecord as $Record){
                      if($value['id'] == $Record['fid']){
                          $subject[$item]['userAnswer'][]= $Record->toArray();
                      }
                }
          }
          $subject = $this->TwoChange($subject);

          return json_encode(['data'=>$subject,'score'=>$userRecord[0]->score]);
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
        $TestPaper =  DB::select('select (select sum(score * topicNum) from papertype WHERE papertype.paperId = test_paper.id) as score, name , id, created_at, updated_at  from test_paper');
         return json_encode($TestPaper);
    }

    //添加试卷
    public function TestPaperAdd(Request $request){
        $TestName = $request->input('TestPaperName');
        $TestPaperId =  TestPaper::create([   //插入并返回id
            'name'=>$TestName
        ])->id;
        return  json_encode(['status'=>200,'message'=>'成功','data'=>['id'=>$TestPaperId]]);
    }

    //添加试卷类型分数和题数
    public function AddPaperType(Request $request){
         $paperId = $request->input('paperId');
         $paperName = $request->input('paperName');
         $paperScore = $request->input('paperScore');
         $paperTopicNum = $request->input('topicNum');

          $paperType = new PaperType();
          $paperType->typeName = $paperName;
          $paperType->score = $paperScore;
          $paperType->topicNum = $paperTopicNum;
          $paperType->paperId = $paperId;
          $paperType->save();
          return json_encode(['status'=>200,'data'=>'success!']);
    }

    //查询试卷和试卷类型
    public function SelectPaperType(){
         $TestPaper = new TestPaper();
         $data = $TestPaper->with('SelectType')->get();
         return json_encode(['status'=>'200','data'=>$data]);
    }
    //添加答案处理
    public function AddAnswer(Request $request){
          $data = $request->input('data');
          $data = json_decode($data,true);
          $PaperType = PaperType::where('id',$data['typeId'])->get();
          $score = $PaperType[0]['score'];
          $type = $PaperType[0]['typeName'];
          $answer = $data['Zanswer'];
          if($type == '多选题'){
                $answerArr = $answer;
                $answer = '';
                for ($i=0; $i<count($answerArr);$i++){
                    if($i!=count($answerArr)-1){
                        $answer .= $answerArr[$i].',';
                    }else{
                        $answer .=$answerArr[$i];
                    }
                }
          }
          $topic = $data['topic'];
//          $topicArr=explode("\n",$topic);
//          $topic = '';
//          foreach ($topicArr as $value){
//              $topic.=$value."<br/>";
//          }
          $subjectId = Subject::create([
              'final'=>$answer,
              'content'=>$topic,
              'fid'=>0,
              'parId'=>$data['paperId'],
              'score'=>$score,
              'type'=>$type
           ])->id;
        if($type == '判断题'){
            return json_encode(['status'=>200]);
        }
          $arr = [];
          foreach ($data['Answer'] as $item){
              $arr[] = ['subjectId'=>$subjectId,'value'=>$item['title'],'content'=>$item['value']];
          }
          Answer::insert($arr);

          return json_encode(['status'=>200]);
    }

    public function DeletePaper(Request $request){
          $id = $request->input('paperId');
          PaperType::where('paperId',$id)->delete();
          DB::delete("delete answer from answer,subject where subject.parId =$id and answer.subjectId = subject.id ");
          Subject::where('parId',$id)->delete();

          TestPaper::where('id',$id)->delete();
          return json_encode(['status'=>200]);
    }
    public function findPaper(Request $request){
         $id = $request->input('id');
         $data = TestPaper::where('id',$id)->with('SelectType')->get();
         $data= $data[0];
         return json_encode(['status'=>200,'data'=>$data]);
    }
    public function updatePaper(Request $request){
       $id = $request->input('id');
       $content = $request->input('content');
       $testPaper = TestPaper::find($id);
       $testPaper->name = $content;
       $testPaper->save();
       return json_encode(['status'=>200]);
    }
    public function updateType(Request $request){
        $parId = $request->input('paperId');
        $id = $request->input('id');
        $score = $request->input('score');
        $typeNum = $request->input('typeNum');
        $paperType = PaperType::find($id);
        $typeName = $paperType['typeName'];
        $paperType->score = $score;
        $paperType->topicNum = $typeNum;
        $paperType->save();

        Subject::where(function ($query) use ($parId,$typeName){
            $query->where('parId',$parId)->where('type',$typeName);
        })->update(['score'=>$score]);
        return json_encode(['status'=>200]);
    }



    public function getPaper(Request $request){
        $currentNum = $request->input('currentNum');
        $page = ($currentNum - 1)  * 5;
        $TestPaper =  DB::select("select (select sum(score * topicNum) from papertype WHERE papertype.paperId = test_paper.id) as score, name , id, created_at, updated_at  from test_paper limit $page,5 ");
        $num = TestPaper::paginate(5);
        return json_encode(['num'=>$num,'data'=>$TestPaper]);
    }

    public function getTopic(Request $request){
        $currentNum = $request->input('currentNum');
        $page = ($currentNum - 1)  * 5;
        $data  = Subject::offset($page)->limit(5)->get();
        $num = Subject::paginate(5);
        return json_encode(['num'=>$num,'data'=>$data]);
    }
    public function selectTopic(Request $request){
        $type = $request->input('type');
        $value = $request->input('value');
        $value = json_decode($value,true);
        $currentNum = $request->input('currentNum');
        $jump = $request->input('jump');
        $page = ($currentNum - 1)  * 5;
        if($value[1] == ''){
            $this->arr = [$type=>$value[0]];
        }else{
            $this->arr = [$type=>$value[0],'parId'=>$value[1]];
        }
        if($type != 'name'){
            $num = Subject::where($this->arr)->paginate(5);
            $result= $this->judeSelect([$jump,new Subject(),$this->arr,$page,0]);
        }else{
                $config = function ($query){
                    foreach ($this->arr as $key=>$value){
                        switch ($key){
                            case 'name':
                                $query->where('content', 'like', '%' . $value . '%');
                                break;
                            case 'parId':
                                $query->where('parId',$value);
                                break;
                        }
                    }
                };
            $result= $this->judeSelect([$jump,new Subject(),$config,$page,0]);
                $num = Subject::where($config)->paginate(5);
        }
        return json_encode(['status'=>200,'data'=>$result,'num'=>$num]);
    }

    function findTopic(Request $request){
           $id = $request->input('id');
           $subject = Subject::where('id',$id)->with('Answer')->get();
           $data = $subject[0];
           return json_encode(['status'=>200,'data'=>$data]);
    }

    function updateTopic(Request $request){
       $id= $request->input('paperId');
       $content = $request->input('content');
       $final = $request->input('final');
       $answer = $request->input('answer');
       $type = $request->input('type');
       if($type == '多选题'){
         $final = json_decode($final,true);
         $str = '';
         for($i=0;$i<count($final);$i++){
             if($final[$i] == ''){
                 continue;
             }
             if($i == count($final)-1){

                 $str .= $final[$i];
             }else{
                 $str .= $final[$i] .',';
             }
         }
         $final =$str;
       }
       $subject = Subject::find($id);
       $subject->content = $content;
       $subject->final = $final;
       $subject->save();
      $answer = json_decode($answer,true);
      $arr=[];
      foreach ($answer as $value){
          Answer::where('id',$value['id'])->update(['content'=>$value['content']]);
      }
        return json_encode(['status'=>200]);
    }

    function judeSelect($arr){

            if($arr[4] == 0) {

                if ($arr[0] == 0) {
                    $result = $arr[1]->where($arr[2])->offset(0)->limit(5)->get();
                } else {
                    $result = $arr[1]->where($arr[2])->offset($arr[3])->limit(5)->get();
                }

            }else{

                if($arr[0] == 0){
                    $result = $arr[1]->where('name','like','%'.$arr[2][1].'%')->offset($arr[3])->limit(5)->get();
                }else {
                    $result = $arr[1]->where('name','like','%'.$arr[2][1].'%')->offset($arr[3])->limit(5)->get();
                }

            }

            return $result;
        }

       public function selectPaper(Request $request){
        $type = $request->input('type');
        $value = $request->input('value');
        $currentNum = $request->input('currentNum');
        $jump = $request->input('jump');
        $page = ($currentNum - 1)  * 5;
        if($type == 'id'){
            $num = TestPaper::where('id',$value)->paginate(5);
            $result= $this->judeSelect([$jump,new TestPaper(),[['id',$value]],$page,0]);
        }else{
            $num = TestPaper::where('name','like','%'.$value.'%')->paginate(5);
            $result= $this->judeSelect([$jump,new TestPaper(),['id',$value],$page,1]);

        }
        return json_encode(['status'=>200,'data'=>$result,'num'=>$num]);
    }

    public function DeleteTopic(Request $request){
        $id = $request->input('paperId');
        DB::delete("delete answer from answer,subject where subject.parId =$id and answer.subjectId = subject.id ");
        Subject::where('id',$id)->delete();
         return json_encode(['status'=>200]);
    }
}
