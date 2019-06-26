<?php
namespace app\controllers\controller;

use app\event\ReleaseCheck;
use app\event\ReleaseHandles;

class Release
{

    public function qr(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $params = request()->post('');
        $check_event   = new ReleaseCheck();
        $handles_event = new ReleaseHandles();

        if(($check_res = $check_event->checkQrParams($params)) && $check_res['errCode'] != 200 ){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleQrRes()) && $handles_res['errCode'] != 200){
            return json($handles_res);
        }

        return json($res);
    }
}