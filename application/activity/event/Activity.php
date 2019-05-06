<?php
namespace app\activity\event;

use app\activity\model\ActivityDetail;
use app\base\controller\Base;
use app\activity\model\Activity as ActivityModel;
use app\activity\model\ActivityDetail as ActivityDetailModel;

class Activity extends Base
{
    public function getActivityListOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = $this->data['page']; // 页数
        $num  = '10';  // 每页数量
        $model = new ActivityModel();
        if(!($res = $model->getActivityPageList(['status'=>1],$page,$num,'id,img'))){
            $Res['errCode'] = '10101';
            $Res['errMsg']  = '列表获取失败';
            return $Res;
        }
        $Res['data'] = selectDataToArray($res);
        return $Res;
    }

    public function getActivityInfoOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new ActivityModel();
        if(!($res = $model->getOneActivityInfo(['status'=>1,'id'=>$this->data['param']['id']]))){
            $Res['errCode'] = '10102';
            $Res['errMsg']  = '详情获取失败';
            return $Res;
        }
        $Res['data'] = findDataToArray($res);
        return $Res;
    }

    public function getActivityReleaseOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new ActivityDetail();
        $data = $this->_getAddActivityDetailData();
        if(!($res = $model->getAddActivityDetailRes($data))){
            $Res['errCode'] = '10102';
            $Res['errMsg']  = '新增失败';
            return $Res;
        }
        return $Res;
    }

    protected function _getAddActivityDetailData(){
        return [
            'uid' => $this->data['param']['uid'],
            'activity_id' => $this->data['param']['aid'],
            'cover' => $this->data['param']['img'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'content'=>$this->data['param']['content'],
        ];
    }
}