<?php
namespace app\controllers\controller;

use app\event\IntegralCheck;
use app\event\IntegralHandles;

class Integral
{
    public function toAdd(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '完成每日登陆任务!积分+50',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new IntegralCheck();
        $handles_event = new IntegralHandles();

        if(($check_res = $check_event->checkToAddParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToAddRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res['data'];
        return json($res);
    }
}
