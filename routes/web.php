<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test','Test\Test@index');


//注册路由
Route::group([],function(){
    Route::get('register','Register\register@index');
    Route::get('testing','Register\register@testing');
    Route::get('registerCode','Register\registerCode@index');
});

//登录路由
Route::group([],function (){
    Route::get('login','Login\login@index');
});

//对票操作
Route::group([],function (){
    Route::get('booking','Booking\booking@index');
    Route::post('TickAdd','Booking\TickAdd@index');
    Route::get('getSeat','Booking\ShopTick@get');
});
//博客操作
Route::group([],function (){
     Route::get('GetDirectory','Blog\index@GetDirectory');
    Route::get('GetBlogContent','Blog\index@GetContent');
    Route::get('GetBlogDetails','Blog\index@GetDetails');
    Route::get('BlogQueryPagin','Blog\index@QueryPagin');
    Route::get('BlogDelete','Blog\index@delete');
      Route::get('DirectoryDelete','Blog\index@DirectoryDelete');
      Route::get('SetBrowse','Blog\Browse@index');
      Route::get('BlogTop','Blog\Browse@Top');
      Route::get('GetComment','Blog\Comment@Handel');
      Route::get('GetHomeBlog','Blog\index@HomeGet');
      Route::post('BlogUpdate','Blog\index@BlogUpdate');
});

//验证中间件
Route::group(['middleware'=>['ApiCret']],function (){
    Route::get('BlogCommentSend','Blog\Comment@index');
    Route::post('ShopTick','Booking\ShopTick@index');
    Route::post('UserSelect','Booking\booking@userSelect');
    Route::post('SnakeUpload','Snake\Snake@SnakeUpload');
    Route::get('comparison','Examination\examinationController@comparison');
    Route::get('AddBlogDirectory','Blog\index@AddDirectory');
    Route::post('BlogUpload','Blog\upload@BlogUpload');
    Route::post('AddBlogContent','Blog\index@AddContent');
    Route::get('AllDirectory','Blog\index@getBlogDirectory');
    Route::post('BlogPort','Blog\Port@PortUpload');
    Route::get('GetPort','Blog\index@GetPort');
    Route::get(' SetLike','Blog\index@SetLike');
});

//考试操作
Route::group([],function (){
    Route::get('testEx','Examination\examinationController@index');
    Route::post('ExResult','Examination\examinationController@ExResult');
    Route::post('getExamination','Examination\examinationController@getExamination');
    Route::get('ExAdd','Examination\examinationController@TestPaperAdd');
    Route::post('paperTypeAdd','Examination\examinationController@AddPaperType');
    Route::get('SelectPaperType','Examination\examinationController@SelectPaperType');
    Route::get('AddAnswer','Examination\examinationController@AddAnswer');
    Route::get('DeletePaper','Examination\examinationController@DeletePaper');
    Route::get('findPaper','Examination\examinationController@findPaper');
    Route::get('updatePaper','Examination\examinationController@updatePaper');
    Route::get('updateType','Examination\examinationController@updateType');
    Route::get('selectPaper','Examination\examinationController@selectPaper');
    Route::get('selectTopic','Examination\examinationController@selectTopic');
    Route::post('getPaper','Examination\examinationController@getPaper');
    Route::post('getTopic','Examination\examinationController@getTopic');
    Route::get('findTopic','Examination\examinationController@findTopic');
    Route::get('updateTopic','Examination\examinationController@updateTopic');
    Route::get('DeleteTopic','Examination\examinationController@DeleteTopic');
});




