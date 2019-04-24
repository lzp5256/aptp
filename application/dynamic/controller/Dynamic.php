<?php
namespace  app\dynamic\controller;

use app\base\controller\Base;
use app\dynamic\event\Check;
use app\dynamic\event\Handle;

class Dynamic extends Base
{
    public function info(){
        $Result = ['errCode' => '200', 'errMsg'  => '查询成功', 'data' => []];
        $param = request()->post('');
        $check_event = new Check();
        if(($check_res = $check_event->checkDynamicInfoParam($param)) && $check_res['errCode']!='200'){
            return json($check_res);
        }
        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->handleDynamicInfo()) && $handle_res['errCode']!='200'){
            return json($handle_res);
        }
        $Result['data'] = $handle_res['data'];
        return json($Result);
    }
}