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
     * @desc 申请
     * @return \think\response\Json
     */
    public function apply()
    {
        $params = request()->param();
        $checkEvent = new CheckEvent();
        if(($checkRes = $checkEvent->checkApplyParams($params)) && $checkRes['errCode'] != '200'){
            return json($checkRes);
        }
        $handleEvent = new DemandEvent();
        $handleRes = $handleEvent->handleApply($checkRes['data']);
        return json($handleRes);
    }

    /**
     * @desc 获取我的送养信息
     * @date 2019.03.10
     * @return \think\response\Json
     */
    public function getMyReleases()
    {
        $params = request()->param();
        $checkEvent = new CheckEvent();
        if(($checkRes = $checkEvent->checkMyReleaseParams($params)) && $checkRes['errCode'] != '200' ){
            return json($checkRes);
        }
        $handleEvent = new DemandEvent();
        $handleRes = $handleEvent->handleMyRelease($checkRes['data']);
        return json($handleRes);
    }

}