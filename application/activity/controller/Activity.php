<?php
namespace app\activity\controller;

use app\base\controller\Base;
use app\activity\event\Activity as ActivityEvent;

class Activity extends Base
{
    public function getActivityList(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $param = request()->post();
        if(!(int)$param['page']){
            $Res['errCode'] = '10100';
            $Res['errMsg']  = 'failed';
            return $Res;
        }
        $event = new ActivityEvent();
        if(!($handle_res = $event->setData(['page'=>(int)$param['page']])->getActivityListOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }
        $Res['data'] = $handle_res['data'];
        return json($Res);
    }

}