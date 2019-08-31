<?php
namespace app\controllers\controller;

use app\base\controller\Base;
use app\event\archives\ArchivesCheck;
use app\event\archives\ArchivesHandles;

class Archives extends Base
{
    public function toCreate(){
        $param = request()->post();
        $check_event   = new ArchivesCheck();
        $handles_event = new ArchivesHandles();

        if(($check_res = $check_event->checkToCreate($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCreate()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    public function toList(){
        $param = request()->post();
        $check_event   = new ArchivesCheck();
        $handles_event = new ArchivesHandles();

        if(($check_res = $check_event->checkToList($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToList()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }
}
