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

//验证中间件
Route::group(['middleware'=>['ApiCret']],function (){
    Route::post('ShopTick','Booking\ShopTick@index');
    Route::post('UserSelect','Booking\booking@userSelect');
    Route::post('SnakeUpload','Snake\Snake@SnakeUpload');
});

//考试操作
Route::group([],function (){
    Route::post('testEx','Examination\examinationController@index');
    Route::get('comparison','Examination\examinationController@comparison');
    Route::post('ExResult','Examination\examinationController@ExResult');
    Route::post('getExamination','Examination\examinationController@getExamination');

});




