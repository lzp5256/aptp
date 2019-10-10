<?php
namespace app\controllers\controller;

use app\base\controller\Base;
use app\event\Circle\CircleCheck;
use app\event\Circle\CircleHandles;

class Circle extends Base
{
    // 获取宠圈列表 | 第一次只获取左边列表和右边第一列
    // Author:lizhipeng
    // Date:2019.09.22
    // Return:json
    public function toList()
    {
        $param = request()->post();
        $check_event   = new CircleCheck();
        $handles_event = new CircleHandles();

        if(($check_res = $check_event->checkToCirCleListParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCircleListRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 获取推荐宠圈列表
    // Author:lizhipeng
    // Date:2019.09.23
    // Return:json
    public function toRecommend()
    {
        $param = request()->post();
        $handles_event = new CircleHandles();

        if(($handles_res = $handles_event->handleToRecommendRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 加入宠圈
    // Author:李志鹏
    // Date:2019.10.06
    // Return:json
    public function toJoinCircle()
    {
        $param = request()->post();
        $check_event   = new CircleCheck();
        $handles_event = new CircleHandles();

        if(($check_res = $check_event->checkToJoinCircleParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToJoinCircleRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 宠圈详情
    // Author:李志鹏
    // Date:2019.10.10
    // Return:json
    public function toInfo()
    {
        $param = request()->post();
        $check_event   = new CircleCheck();
        $handles_event = new CircleHandles();

        if(($check_res = $check_event->checkToCircleInfoParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCircleInfoRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 宠圈详情
    // Author:李志鹏
    // Date:2019.10.10
    // Return:json
    public function toDetail()
    {
        $param = request()->post();
        $check_event   = new CircleCheck();
        $handles_event = new CircleHandles();

        if(($check_res = $check_event->checkToCircleDetailParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCircleDetailRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }
}