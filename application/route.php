<?php
use think\Route;

//Route::get('test','index\Test\test1');
Route::get('/',function(){
   return 'hello function';
});

// 测试路由分组
Route::group('test',function (){
    Route::get('test1','index/test/test1');
});

// 微信相关路由
Route::group('wechat',function(){
    // 获取微信
    Route::post('GetToken','wechat/token/getWechatToken');
});

// 七牛相关路由
Route::group('qiniu',function (){
    // 获取七牛token
    Route::post('','');
});

// 用户路由分组
Route::group('user',function (){
    //Route::get('hello','index/user/hello',['before_behavior'=>'app\index\behavior\UserCheck']);
    Route::post('test111','user/user/test',['before_behavior'=>'app\behavior\UserTest']);
});

// 首页路由分组
Route::group('index',function (){

});