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
    Route::post('GetQiniuToken','qiniu/qiniu/getQiniuToken');
});

// 首页路由分组
Route::group('index',function (){
    Route::post('GetReList','index/index/getReList'); //获取首页推荐列表-
});


// 公共路由分组
Route::group('communal',function (){
    Route::post('GetBanners','communal/communal/getBanners'); //获取banner
    Route::post('GetIcons','communal/communal/getIcons'); // 获取icon
    Route::post('GetCitys','communal/communal/getCitys'); // 获取所有地区信息
});