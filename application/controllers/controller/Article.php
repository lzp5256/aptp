<?php
namespace app\controllers\controller;

use app\event\ArticleCheck;
use app\event\ArticleHandles;

class Article
{
    public function toInfo()
    {
        $res = [
            'errCode' => '200',
            'errMsg'  => '成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToInfoParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToInfoRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res['data'];
        return json($res);
    }
}