<?php
namespace app\activity\event;

use app\activity\model\ActivityDetail;
use app\base\controller\Base;
use app\activity\model\Activity as ActivityModel;
use app\activity\model\ActivityDetail as ActivityDetailModel;
use app\helper\helper;
use app\user\event\User;

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
        $model = new ActivityDetailModel();
        $data = $this->_getAddActivityDetailData();
        if(!($res = $model->getAddActivityDetailRes($data))){
            $Res['errCode'] = '10102';
            $Res['errMsg']  = '新增失败';
            return $Res;
        }
        return $Res;
    }

    public function getActivityWorksListOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new ActivityDetailModel();
        $num = 10;
        if(!($res = $model->getActivityDetailPageList(['status'=>1,'activity_id'=>$this->data['param']['aid']],$this->data['param']['page'],$num))
        && empty($res)
        ){
            $Res['errCode'] = '10102';
            $Res['errMsg']  = '暂无更多数据';
            return $Res;
        }
        $arr = selectDataToArray($res);

        // 获取用户信息
        if(!($userList = $this->_getUserList($arr)) && empty($userList)){
            $userList = [];
        }

        $idToImageList = [];
        foreach ($arr as $k => $v){
            $arr[$k]['cover']=json_decode($v['cover'],true);
            $arr[$k]['user'] = $userList[$v['uid']];
            foreach ($arr[$k]['cover'] as $k1 => $v1){
                $idToImageList[$v['id']][] =$v1['img'];
            }
        }
        foreach ($arr as $k => $v){
            $arr[$k]['cover'] = $idToImageList[$v['id']];
        }

        $Res['data'] = $arr;
        return $Res;
    }

    public function getActivityWorksDetailOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $helper = new helper();
        $model = new ActivityDetailModel();
        if(!($res = $model->getOneActivityDetailInfo(['status'=>1,'id'=>$this->data['param']['id']]))){
            $Res['errCode'] = '10102';
            $Res['errMsg']  = '详情获取失败';
            return $Res;
        }

        $res = findDataToArray($res);

        $getUserInfo = $helper->setData(['uid'=>$res['uid']])->GetUserStatusById();
        $getUserInfo['name'] = base64_decode($getUserInfo['name']);
        $res['user'] = $getUserInfo;

        // 获取所属活动
        $getActivityInfo = $helper->setData(['activity_id'=>(int)$res['activity_id']])->GetActivityInfoById();
        $res['activity'] = $getActivityInfo;

        $idToImageList = [];
        $cover = json_decode($res['cover'],true);
        foreach ($cover as $k => $v){
            $idToImageList[$res['id']][] =$v['img'];
        }
        $res['cover_arr'] = $idToImageList[$res['id']];
        $Res['data'] = $res;
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

    protected function _getUserList($arr){
        $event = new User();
        $user_id = [];
        foreach ($arr as $k => $v){
            $user_id[] = $v['uid'];
        }
        $un_user_id = array_unique($user_id);
        $getAllUserList = $event->setData(['uid'=>$un_user_id])->getAllUserList();
        if(!$getAllUserList){
            return [];
        }
        return $getAllUserList;
    }
}