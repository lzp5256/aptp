<?php
namespace app\activity\controller;

use app\base\controller\Base;
use app\activity\event\Activity as ActivityEvent;
use app\activity\event\Check;

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

    public function getActivityInfo(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        $param = request()->post();

        $checkEvent = new Check();
        if(!($checkRes = $checkEvent->checkGetInfoParam($param)) || $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        $event = new ActivityEvent();
        if(!($handle_res = $event->setData($checkRes['data'])->getActivityInfoOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }
        $Res['data'] = $handle_res['data'];
        return json($Res);
    }

    public function getActivityReleaseRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => '发布成功,审核成功后会展示到相应的活动列表.感谢您参加本活动,祝您生活愉快!',
            'data'    => [],
        ];

        $param = request()->post();

        $checkEvent = new Check();
        if(!($checkRes = $checkEvent->checkActivityReleaseParam($param)) || $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        $event = new ActivityEvent();
        if(!($handle_res = $event->setData($checkRes['data'])->getActivityReleaseOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }
        return json($Res);
    }

    public function getActivityWorksRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        $param = request()->post();

        $checkEvent = new Check();
        if(!($checkRes = $checkEvent->checkActivityWorksListParam($param)) || $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        $event = new ActivityEvent();
        if(!($handle_res = $event->setData($checkRes['data'])->getActivityWorksListOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }
        $Res['data'] = $handle_res['data'];
        return json($Res);
    }

    public function getActivityWorksDetailRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $param = request()->post();

        $checkEvent = new Check();
        if(!($checkRes = $checkEvent->checkActivityWorksDetailParam($param)) || $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        $event = new ActivityEvent();
        if(!($handle_res = $event->setData($checkRes['data'])->getActivityWorksDetailOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }

        $Res['data'] = $handle_res['data'];
        return json($Res);
    }

    public function getActivityWorksCommentListRes(){

        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $param = request()->post();

        $checkEvent = new Check();
        if(!($checkRes = $checkEvent->checkActivityWorksCommentListParam($param)) || $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        $event = new ActivityEvent();
        if(!($handle_res = $event->setData($checkRes['data'])->getActivityWorksCommentListOfEvent()) || $handle_res['errCode']!= '200'){
            return json($handle_res);
        }

        $Res['data'] = $handle_res['data'];
        return json($Res);
    }


}