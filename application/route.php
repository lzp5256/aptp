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

// 首页路由分组
Route::group('index',function (){
    //Route::post('GetBanners','index/index/getBanners');
});


// 公共路由分组
Route::group('communal',function (){
    Route::post('GetBanners','communal/communal/getBanners');
});