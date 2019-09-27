<?php
use think\Route;

// 公共路由分组
Route::group('communal',function (){
    Route::post('GetBanners','communal/communal/getBanners'); //获取banner
    Route::post('GetIcons','communal/communal/getIcons'); // 获取icon
    Route::post('GetCitys','communal/communal/getCitys'); // 获取所有地区信息
//    Route::post('detail','communal/communal/detail'); // 详情（不需要验证token）
    Route::post('getRsaToken','communal/communal/getRsaToken'); //获取加密字符串
    Route::post('getAdoptData','communal/communal/getAdoptData'); //获取领养发布页面数据
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
});

// 领养路由
Route::group('adopt',function (){
    Route::post('add','adopt/adopt/getAddAdoptRes'); // 发布新增领养
    Route::post('detail','adopt/adopt/getAdoptDetailRes');// 详情
});


// 需求路由
Route::group('demand',function (){
    Route::post('release','demand/demand/release'); //发布
    Route::post('apply','demand/demand/apply'); //申请
});

// 用户动态路由
Route::group('trends',function (){
    Route::post('release','trends/trends/release'); // 用户发布动态
    Route::post('info','trends/trends/info'); // 用户动态详情
    Route::post('list','trends/trends/index'); // 用户动态详情
});


/**************************************  pet route **************************************/
// 福利路由
Route::group('welfare',function (){
    Route::post('list','controllers/welfare/wl');     // 列表
});

// 积分路由
Route::group('integral',function (){
    Route::post('add','controllers/integral/toAdd');     // 新增积分
});

// 文章路由
Route::group('article',function (){
    Route::post('create','controllers/article/toCreate'); // 创建动态
    Route::post('list','controllers/article/toList'); // 获取文章列表
    Route::post('newList','controllers/article/toNewList'); // 获取最新文章或动态列表
    Route::post('info','controllers/article/toInfo'); // 获取文章详情
    Route::post('recommend','controllers/article/toRecommend'); // 获取推荐文章
    Route::post('browse','controllers/article/toBrowse');   // 文章浏览
    Route::post('comment','controllers/article/toComment'); // 文章评论
    Route::post('commentList','controllers/article/toCommentList'); // 文章评论列表
    Route::post('like','controllers/article/toLike'); // 文章点赞
});

// 用户路由
Route::group('user',function (){
    Route::post('info','controllers/user/toUserInfo'); // 获取用户详情
    Route::post('edit','controllers/user/toEditInfo'); // 编辑个人资料
    Route::post('follow','controllers/user/toFollow'); // 关注
    Route::post('followList','controllers/user/toFollowList'); // 关注列表
    Route::post('trends','controllers/user/toTrendsList'); // 我发布的动态列表
});


// 宠物档案
Route::group('archives',function (){
    Route::post('create','controllers/archives/toCreate'); // 创建宠物档案
    Route::post('list','controllers/archives/toList'); // 获取宠物档案列表
});

// 宠圈
Route::group('circle',function (){
    Route::post('list','controllers/circle/toList'); // 宠圈列表
    Route::post('recommend','controllers/circle/toRecommend'); // 推荐宠圈
});












/**************************************  ask route **************************************/
// 问题路由
Route::group('question',function (){
    Route::post('release','controllers/release/qr');   // 发布
    Route::post('list','controllers/question/ql');     // 列表
    Route::post('browse','controllers/question/qb');   // 浏览
    Route::post('info','controllers/question/qi');     // 详情
    Route::post('comment','controllers/question/qc');  // 评论
});


/**************************************  rc route **************************************/
Route::group('rc',function (){
    Route::post('add','controllers/rc/ra');
    Route::post('search','controllers/rc/rs');
    Route::post('info','controllers/rc/ri');
});
