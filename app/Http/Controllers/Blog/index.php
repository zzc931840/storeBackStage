<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Register\register;
use App\userInfo;
use App\Users;
use Illuminate\Http\Request;
use App\BlogDirectory;
use App\BlogContent;
use App\Http\Controllers\Tools\Factory;
use App\userLike;
class index extends Controller
{
    //
    function AddDirectory(Request $request){
        $userId =  $request->input('user_id');
        $name = $request->input('name');
        $blog  = new BlogDirectory();
        $blog->userId = $userId;
        $blog->name = $name;
        $blog->save();
        return json_encode(['status'=>200,'message'=>'成功']);
    }

    public function  GetDirectory(Request $request){

            $Blog = BlogDirectory::get();

            return json_encode($Blog);
}
   public function GetDetails(Request $request){
       $id = $request->input('id') ;
       $Blog =  BlogContent::where('id',$id)->get();
       return json_encode($Blog);
   }
   public function AddContent(Request $request){
       $userId =  $request->input('user_id');
       $did = $request->input('did');
       $content = $request->input('content');
       $title = $request->input('title');
       $blog  = new BlogContent();
       $blog->uid = $userId;
       $blog->did = $did;
       $blog->content = $content;
       $blog->title = $title;
       $blog->save();
       return json_encode(['status'=>200,'message'=>'成功']);
   }
   public function getContent(Request $request){
        $total = '';
        $jude = 0;
       if($request->has('jude')) {
           $jude = 1;
       }
      $value =  $request->input('value');
        if($request->has('id')){
            $id = $request->input('id');
            $data  = BlogContent::where('id',$id)->with('GetDirectory')->with('GetUser')->withCount('GetBrowse')->get();
        }else if($value == ''){
            $currentNum = $request->input('currentNum');
            $page = ($currentNum - 1)  * 5;
            $data  = BlogContent::offset($page)->limit(5)->with('GetDirectory')->with('GetUser')->withCount('GetBrowse')->with('GetUserLike')->withCount('GetComment')->withCount('GetUserLikeCount')->orderBy('id','desc')->get();
            $total = BlogContent::count();
        }else{
            $currentNum = $request->input('currentNum');
            $page = ($currentNum - 1)  * 5;
            $data  = BlogContent::offset($page)->join('blog_directory','did','blog_directory.id')->where('title','like','%'.$value.'%')->orWhere('name','like','%'.$value.'%')->limit(5)->with('GetDirectory')->with('GetUser')->withCount('GetBrowse')->with('GetUserLike')->withCount('GetComment')->withCount('GetUserLikeCount')->orderBy('id','desc')->get();
            $total = BlogContent::count();
        }
       foreach ($data as $key=>$value){
        if($jude == 1){
               $data[$key]['content'] = $this->StringToText($value['content'], 150);
           }
           $fid = $value['GetDirectory']['id'];
            $count= BlogContent::where('did',$fid)->count();
            $data[$key]['count']=$count;
            $sunSet = BlogContent::where('did',$fid)->orderBy('id','asc')->get();
            foreach ($sunSet as $t=>$v){
                   if($v['id'] == $value['id']){
                       $data[$key]['currentKey'] =$t +1;
                   }
            }
       }
       $num = BlogContent::paginate(5);
       return json_encode(['num'=>$num,'data'=>$data,'total'=>$total]);
   }

   public function getBlogDirectory(Request $request){
       $userId =  $request->input('user_id');
      $data = BlogDirectory::where('userId',$userId)->with(['GetContent'=>function($query){
         return $query->select('id','did','title');
      }])->get();
       return json_encode(['status'=>200,'data'=>$data]);
   }

   public function QueryPagin(Request $request){
                    $select =   $request->input('select');
                    $current = $request->input('current');
                    $Blog  =  Factory::Get('BlogContent');
                    $arr=['current'=>$current,'condition'=>['title',$select],'obj'=>$Blog,'rela'=>['GetDirectory','GetUser']];
                   $result = $this->PaginFunc($arr);
                   return json_encode($result);
   }

   public function PaginFunc($arr){
            $page = ($arr['current']- 1)  * 5;
            $data = $arr['obj']->where($arr['condition'][0],'like','%'.$arr['condition'][1].'%')->offset($page)->limit(5);
            foreach ($arr['rela'] as $value){
                   $data = $data->with($value);
            }
             $result =  $data->get();
             $total = $arr['obj']->where($arr['condition'][0],'like','%'.$arr['condition'][1].'%')->count();
             return ['result'=>$result,'total'=>$total];

   }
   public function delete(Request $request){
                  $id  = $request->input('id');
                  $blog = Factory::Get('BlogContent');
                  $blog->where('id',$id)->delete();
                  return json_encode(['status'=>200]);
   }
   public function DirectoryDelete(Request $request){
                $id =  $request->input('id');
                 $blog =   Factory::Get('BlogDirectory');
                 $blog->where('id',$id)->delete();
                 return json_encode(['status'=>200]);
   }

   public function HomeGet(){
          $data = BlogContent::orderBy('id','desc')->limit(3)->get();
          foreach ($data as &$value){
                   $text = $this->StringToText($value['content'],45);
                   $value['content'] = $text;
          }
          return $data;
   }

    /**
     * 提取富文本字符串的纯文本,并进行截取;
     * @param $string 需要进行截取的富文本字符串
     * @param $int 需要截取多少位
     */
    function StringToText($string,$num){
        if($string){
            //把一些预定义的 HTML 实体转换为字符
            $html_string = htmlspecialchars_decode($string);
            //将空格替换成空
            $content = str_replace(" ", "", $html_string);
            $content = str_replace(PHP_EOL, '', $content);
            $content=str_replace('&nbsp;','',$content);

            //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
            $contents = strip_tags($content);
            //返回字符串中的前$num字符串长度的字符
            return mb_strlen($contents,'utf-8') > $num ? mb_substr($contents, 0, $num, "utf-8").'....' : mb_substr($contents, 0, $num, "utf-8");
        }else{
            return $string;
        }
    }
    function BlogUpdate(Request $request){
          $id  = $request->input('id');
          $content = $request->input('content');
          $blog =  BlogContent::find($id);
          $blog->content = $content;
          $blog->save();
          return json_encode(['status'=>200]);
    }
    function GetPort(Request $request){
        $fid  = $request->input('user_id');
        $blog =  userInfo::where('fid',$fid)->get();
        $user = Users::find($fid);
        $port = $blog[0]->port;
        $name = $user->name;
        return json_encode(['port'=>$port,'name'=>$name]);
    }
    function SetLike(Request $request){
           $id = $request->input('user_id');
           $blogId = $request->input('BlogId');
           $arr =  userLike::where('uid',$id)->where('pid',$blogId)->get();
           if(empty($arr[0])){
               $userLike = new userLike();
               $userLike->pid  = $blogId;
               $userLike->uid = $id;
               $userLike->save();
               return json_encode(['status'=>200]);
           }
           return json_encode(['status'=>202]);
    }

}
