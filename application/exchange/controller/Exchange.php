<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/4/1
 * Time: 4:14 PM
 */
namespace app\exchange\controller;

use app\base\controller\Base;
use app\exchange\event\Check;
use app\exchange\event\Exchange as ExchangeEvent;

class Exchange extends Base
{
    public function __construct(){}

    public function detail()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => '查询成功',
            'data'    => [],
        ];

        $checkEvent = new Check();
        $handleEvent = new ExchangeEvent();
        $params = request()->post();
        if(($checkRes = $checkEvent->checkDetailParams($params)) && $checkRes['errCode']!= '200'){
            return json($checkRes);
        }

        if(($handleRes = $handleEvent->setData($checkRes['data'])->handle_detail()) && $handleRes['errCode'] != '200'){
            return json($handleRes);
        }

        $Result['data'] = $handleRes['data'];
        return json($Result);
    }
}