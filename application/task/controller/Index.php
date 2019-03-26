<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/26
 * Time: 12:08 AM
 */
namespace app\task\controller;

use app\base\controller\Base;
use app\task\event\Check as CheckEvent;
use app\task\event\Index as IndexEvent;
class Index extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function index()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $params = request()->post();

        $checkEvent = new CheckEvent();
        if(($check_res = $checkEvent->checkIndexParams($params)) && $check_res['errCode']!='200'){
            return json($check_res);
        }

        $handleEvent = new IndexEvent();
        if(($handle_res = $handleEvent->setData($check_res['data'])->handle()) && $check_res['errCode']!='200'){
            return json($handle_res);
        }

        $Result['data'] = $handle_res['data'];
        return json($Result);
    }

}