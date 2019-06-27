<?php
namespace app\controllers\controller;

use app\event\QuestionCheck;
use app\event\QuestionHandles;

class Release
{

    public function qr(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $params = request()->post('');
        $check_event   = new QuestionCheck();
        $handles_event = new QuestionHandles();

        if(($check_res = $check_event->checkQrParams($params)) && $check_res['errCode'] != 200 ){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleQrRes()) && $handles_res['errCode'] != 200){
            return json($handles_res);
        }

        return json($res);
    }
}