<?php
namespace app\trends\controller;

use app\base\controller\Base;
use app\trends\event\Check;
use app\trends\event\Handle;

class Trends extends Base
{
    /**
     * @desc 用户动态发布
     * @date 2019.06.03
     * @author lizhipeng
     */
    public function release(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $param = request()->post('');
        $check_event = new Check();
        if(($check_res = $check_event->CheckTrendsReleaseParamRes($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->HandleTrendsReleaseRes()) && $handle_res['errCode'] != '200'){
            return json($handle_res);
        }

        return json($Res);

    }

    /**
     * @desc 获取动态详情
     * @date 2019.06.10
     * @author lizhipeng
     */
    public function info(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => '获取成功',
            'data'    => [],
        ];
        $param = request()->post('');
        $check_event = new Check();
        if(($check_res = $check_event->CheckTrendsInfoParamRes($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->HandleTrendsInfoRes()) && $handle_res['errCode'] != '200'){
            return json($handle_res);
        }

        $Res['data'] = $handle_res['data'];
        return json($Res);
    }
}
