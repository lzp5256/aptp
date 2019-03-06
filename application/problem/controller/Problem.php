<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/6
 * Time: 2:27 PM
 */
namespace app\problem\controller;

use app\base\controller\Base;
use app\problem\event\Check as CommunalCheckEvent;
use app\problem\event\Handle;

class Problem extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function feedback()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $param = request()->param();
        $check = new CommunalCheckEvent();
        if(($checkRes = $check->CheckParam($param)) && $checkRes['errCode'] != '200'){
            return json($checkRes);
        }
        $handle = new Handle();
        if(($handleRes = $handle->handle($checkRes['data'])) && $handleRes['errCode'] != '200'){
            return json($handleRes);
        }

        return json($Result);
    }
}