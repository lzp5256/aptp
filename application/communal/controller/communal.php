<?php
/**
 * 公共方法文件
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:36 PM
 */
namespace app\communal\controller;

use think\Db;

class communal
{

    public function getBanners()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        // 获取最新的banners
        $result = Db::table('banners')->where('status',1)->where('type',1)->order('id desc')->select();
        if(!$result){
            $Result['errCode'] = 'L10005';
            $Result['errMsg'] = '抱歉,未获取到banner信息！';
        }

        foreach ($result as $k => $v) {
            $Result['data']['id']= $v['id'];
            $Result['data']['address']= $v['address'];
            $Result['data']['url']= $v['url'];
        }

        return json($Result);
    }

    public function getIcons()
    {

    }

}