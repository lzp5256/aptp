<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/22
 * Time: 3:28 PM
 */
namespace app\task\controller;

use app\base\controller\Base;
use app\task\event\Check as CheckEvent;
use app\task\event\Sign as SignEvent;

class Sign extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function sign()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => '签到成功',
            'data' => [],
        ];
        $param = request()->param();
        $check_event = new CheckEvent();
        $sign_event = new SignEvent();
        if(($check_res = $check_event->checkParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handle_res = $sign_event->handle($check_res['data'])) && $handle_res['errCode'] != '200'){
            return json($handle_res);
        }

        return json($Result);
    }
}