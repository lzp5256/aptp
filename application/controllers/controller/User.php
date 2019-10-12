<?php
namespace app\controllers\controller;


use app\base\controller\Base;
use app\event\ArticleCheck;
use app\event\ArticleHandles;
use app\event\Message\MessageCheck;
use app\event\Message\MessageHandles;
use app\event\UserCheck;
use app\event\UserHandles;

class User extends Base
{
    // 获取用户详情
    // Author: lizhipeng
    // Return: json
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

    // 编辑个人资料
    // Author: lizhipeng
    // Date: 2019.09.04
    // Return: json
    public function toEditInfo()
    {
        $param = request()->post();
        $check_event   = new UserCheck();
        $handles_event = new UserHandles();

        if(($check_res = $check_event->checkToEditParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToEditRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 用户关注
    // Author:lizhipeng
    // Date:2019.09.23
    // Return:json
    public function toFollow()
    {
        $param = request()->post();
        $check_event   = new UserCheck();
        $handles_event = new UserHandles();

        if(($check_res = $check_event->checkToFollowParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToFollowRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 用户关注列表
    // Author:李志鹏
    // Date:2019.09.25
    // Return:json
    public function toFollowList()
    {
        $param = request()->post();
        $check_event   = new UserCheck();
        $handles_event = new UserHandles();

        if(($check_res = $check_event->checkToFollowListParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToFollowListRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 用户动态列表
    // Author:李志鹏
    // Date:2019.09.27
    // Return:json
    public function toTrendsList()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToTrendsList($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToTrendsListRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 用户消息通知列表
    // Author:李志鹏
    // Date:2019.09.29
    // Return:json
    public function toMessageList()
    {
        $param = request()->post();
        $check_event   = new MessageCheck();
        $handles_event = new MessageHandles();

        if(($check_res = $check_event->checkToMessageList($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToMessageList()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    // 用户统计
    // Author:李志鹏
    // Date:2019.10.12
    // Return:json
    public function toTotal()
    {
        $param = request()->post();
        $check_event   = new UserCheck();
        $handles_event = new UserHandles();

        if(($check_res = $check_event->checkToTotalParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToTotal()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

}