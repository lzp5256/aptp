<?php
namespace app\controllers\controller;


use app\base\controller\Base;
use app\event\UserCheck;
use app\event\UserHandles;

class User extends Base
{
    public function toUserInfo()
    {
        $param = request()->post();
        $check_event   = new UserCheck();
        $handles_event = new UserHandles();

        if(($check_res = $check_event->checkToUserInfoParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToUserInfoRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }


}