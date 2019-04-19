<?php
namespace app\index\controller;

use app\base\controller\Base;
use app\index\event\Index as IndexEvent;


class Index extends Base
{
    public function __construct(){}

    public function getReList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $params = request()->param();
        $event = new IndexEvent();
        if(($res = $event->getReList($params)) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }

    /**
     * @desc 获取首页信息
     */
    public function home(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = request()->post('page');
        $event = new IndexEvent();
        if(($res = $event->setData(['page'=>$page])->getHomeList()) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }


}
