<?php
namespace app\release\controller;

use app\base\controller\Base;
use app\release\event\Check;
use app\release\event\Handle as QaHandle;
class Qa extends Base
{
    /**
     * @desc 发布新的问答
     * @date 2019.04.16
     * @author lizhipeng
     * @return json
     */
    public function release(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $param = request()->post('.');

        $check_event = new Check();
        if(($check_res = $check_event->checkQaParam($param)) && $check_res['errCode']!='200'){
            return json($check_res);
        }

        $handle_event = new QaHandle();
        if(($handle_res = $handle_event->setData($check_res['data'])->handleReleaseQaRes()) && $handle_res['errCode']!='200'){
            return json($check_res);
        }

    }
}