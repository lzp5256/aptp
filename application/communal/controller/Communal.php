<?php
/**
 * 公共方法文件
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:36 PM
 */
namespace app\communal\controller;

use think\Db;

class Communal
{
    /**
     * 获取banner
     *
     * @author lizhipeng
     * @date 2019/01/10
     *
     * @return \think\response\Json
     */
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
            $Result['data'][$k]['id']= $v['id'];
            $Result['data'][$k]['address']= $v['address'];
            $Result['data'][$k]['url']= $v['url'];
        }

        return json($Result);
    }

    /**
     * 获取icon
     *
     * @author lizhipeng
     * @date 2019/01/10
     *
     * @return \think\response\Json
     */
    public function getIcons()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        // 获取最新的banners
        $result = Db::table('banners')->where('status',1)->where('type',2)->order('id desc')->select();
        if(!$result){
            $Result['errCode'] = 'L10006';
            $Result['errMsg'] = '抱歉,未获取到Icon信息！';
        }

        foreach ($result as $k => $v) {
            $Result['data']['id']= $v['id'];
            $Result['data']['address']= $v['address'];
            $Result['data']['url']= $v['url'];
        }

        return json($Result);
    }

}