<?php
namespace app\release\controller;

use app\base\controller\Base;
use app\release\event\Check;
use app\release\event\Handle;

class Article extends Base
{
    public function release(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $param = request()->post('');
        $check_event = new Check();
        if(($check_res = $check_event->checkArticleParam($param)) && $check_res['errCode']!='200'){
            return json($check_res);
        }

        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->handleReleaseArticleRes()) && $handle_res['errCode']!='200'){
            return json($check_res);
        }

        return json($Result);
    }
}
