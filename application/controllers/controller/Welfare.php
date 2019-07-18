<?php
namespace app\controllers\controller;

use app\base\controller\Base;
use app\event\WelfareCheck;
use app\event\WelfareHandles;

class Welfare extends Base
{
    public function wl()
    {
        $res = [
            'errCode' => '200',
            'errMsg'  => '获取成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new WelfareCheck();
        $handles_event = new WelfareHandles();

        if(($check_res = $check_event->checkWlParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleWlRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res['data'];
        return json($res);
    }
}
