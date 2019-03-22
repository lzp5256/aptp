<?php
/**
 * 公共方法文件
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:36 PM
 */
namespace app\communal\controller;

use think\Db;
use app\demand\event\Demand as DemandEvent;
use app\demand\event\CheckParams as CheckEvent;
use app\base\controller\RSAUtils;

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

    /**
     * 获取地区
     *
     * @author lizhipeng
     * @date 2019/01/23
     *
     * @return \think\response\Json
     */
    public function getCitys()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        // 获取所有地区信息
        $result = Db::table('region')->where('status',1)->where('delete_flag',0)->where('rg_level',2)->select();

        if(!$result){
            $Result['errCode'] = 'L10007';
            $Result['errMsg'] = '抱歉,未获取到地区信息！';
        }

        foreach ($result as $k => $v) {
            $Result['data'][$k]['id']= $v['rg_id'];
            $Result['data'][$k]['name']= $v['rg_name'];
            $Result['data'][$k]['pinyin']= $v['pinyin'];
        }

        return json($Result);
    }

    /**
     * @desc 获取需求详情
     * @date 2019.02.19
     * @return json
     */
    public function detail()
    {
        $params = request()->param();
        $checkEvent = new CheckEvent();
        if(($checkRes = $checkEvent->checkDetailParams($params)) && $checkRes['errCode'] != '200'){
            return json($checkRes);
        }
        $handleEvent = new DemandEvent();
        $handleRes = $handleEvent->handleDetail($checkRes['data']);
        return json($handleRes);
    }

    /**
     * @desc 获取Rsa加密字符串
     * @date 2019.03.22
     * @return string
     */
    public function getRsaToken()
    {
        $rsa = new RSAUtils();
        $str = 'muyao';
        $time = date('Ymd');
        $Encrypt = $rsa->pubkeyEncrypt($str.$time);
        return json(['encrypt'=>$Encrypt]);
    }

}