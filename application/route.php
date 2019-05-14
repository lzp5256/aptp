<?php
use think\Route;

//Route::get('test','index\Test\test1');
Route::get('/',function(){
   return 'hello function';
});

// 测试路由分组
Route::group('test',function (){
    Route::post('base64_decode','test/test/decode');
});

// 微信相关路由
Route::group('wechat',function(){
    // 获取微信
    Route::post('GetToken','wechat/token/getWechatToken');
    Route::post('SendMessage','wechat/message/getSendTemplateMessageRes');
});

// 七牛相关路由
Route::group('qiniu',function (){
    Route::get('GetQiniuToken','qiniu/qiniu/getQiniuToken');// 获取七牛token
    Route::get('GetCallBack','qiniu/qiniu/callBack');//回调
});

// 首页路由分组
Route::group('index',function (){
    Route::post('GetReList','index/index/getReList'); //获取首页推荐列表-旧
    Route::post('GetHomeList','index/index/home');    //获取首页推荐列表
});

// 需求路由
Route::group('demand',function (){
    Route::post('release','demand/demand/release'); //发布
    Route::post('apply','demand/demand/apply'); //申请
});

// 我的相关路由
Route::group('my',function (){
    Route::post('myRelease','demand/demand/getMyReleases'); //我发布的
    Route::post('myApplys','demand/demand/getMyApplys'); //我申请的
    Route::post('feedback','problem/problem/feedback'); //问题反馈
});

// 任务路由
Route::group('task',function (){
    Route::post('index','task/index/index'); //任务页面
    Route::post('sign','task/sign/sign'); // 签到
    Route::post('GetExchangeDetail','exchange/exchange/detail'); // 兑换详情
    Route::post('GetUserChangeRes','exchange/exchange/change'); // 兑换操作
});

// 发布路由
Route::group('release',function (){
    Route::post('qa','release/qa/release');
    Route::post('article','release/article/release');
    Route::post('comment','release/comment/release');
    Route::post('like','release/comment/like');
});

// 评论路由
Route::group('detail',function (){
    Route::post('dynamicInfo','dynamic/dynamic/info');
});

// 活动路由
Route::group('activity',function (){
    Route::post('list','activity/activity/getActivityList');
    Route::post('info','activity/activity/getActivityInfo');
    Route::post('release','activity/activity/getActivityReleaseRes');
    Route::post('works','activity/activity/getActivityWorksRes');
    Route::post('detail','activity/activity/getActivityWorksDetailRes');
    Route::post('works_comment_list','activity/activity/getActivityWorksCommentListRes');
});

// 领养路由
Route::group('adopt',function (){
    Route::post('add','adopt/adopt/getAddAdoptRes'); // 发布新增领养
});

// 用户路由
Route::group('user',function (){
    Route::post('GetLoginExpirationForWechat','user/user/getLoginExpirationForWechat');
});

// 公共路由分组
Route::group('communal',function (){
    Route::post('GetBanners','communal/communal/getBanners'); //获取banner
    Route::post('GetIcons','communal/communal/getIcons'); // 获取icon
    Route::post('GetCitys','communal/communal/getCitys'); // 获取所有地区信息
    Route::post('detail','communal/communal/detail'); // 详情（不需要验证token）
    Route::post('getRsaToken','communal/communal/getRsaToken'); //获取加密字符串
    Route::post('getAdoptData','communal/communal/getAdoptData'); //获取领养发布页面数据
});