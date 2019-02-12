<?php
namespace app\index\controller;

use app\base\controller\Base;
use app\index\event\index as IndexEvent;


class Index extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function getReList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $params = request()->param();
        $event = new IndexEvent();
        if(($res = $event->getReList($params)) && $res['errCode'] != '200'){
            return json($res);
        }
        $Result['data'] = $res['data'];
        return json($Result);
    }


}
