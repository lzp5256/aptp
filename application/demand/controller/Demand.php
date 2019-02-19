<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/28
 * Time: 2:24 PM
 */
namespace  app\demand\controller;

use app\base\controller\Base;
use app\demand\event\Demand as DemandEvent;
use app\demand\event\CheckParams as CheckEvent;

class Demand extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
           echo $result['errMsg'];die;
        }
    }

    /**
     * @desc 发布
     * @return \think\response\Json
     */
    public function release()
    {
        $params = request()->param();
        $checkEvent = new CheckEvent();
        if(($checkRes = $checkEvent->checkParams($params)) && $checkRes['errCode'] != '200'){
            return json($checkRes);
        }
        $handleEvent = new DemandEvent();
        $handleRes = $handleEvent->handle($checkRes['data']);
        return json($handleRes);
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
}