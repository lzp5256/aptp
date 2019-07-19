<?php
namespace app\event;

use app\base\controller\Base;
use app\Model\TaskList;
use app\Model\User;

class IntegralCheck extends Base
{
    public function checkToAddParams($params){
        $res = [
            'errCode' => '200',
            'errMsg'  => '',
            'data'    => [],
        ];
        if(empty($params) && is_array($params)){
            return $this->setReturnMsg('100');
        }
        if(!isset($params['uid']) || empty($params['uid']) || $params['uid']<=0){
            return $this->setReturnMsg('00041');
        }
        $this->data['param']['uid'] = (int)$params['uid'];

        if(!isset($params['tid']) || empty($params['tid']) || $params['tid']<=0){
            return $this->setReturnMsg('00042');
        }
        $this->data['param']['tid'] = (int)$params['tid'];

        $user_info = $this->_getUserInfo($params['uid']);
        if(empty($user_info)){
            return $this->setReturnMsg('101');
        }
        $this->data['user_info'] = $user_info;

        $task_info = $this->_getTaskInfo($params['tid']);
        if(empty($task_info)){
            return $this->setReturnMsg('102');
        }
        $this->data['task_info'] = $task_info;
        $res['data'] = $this->data;
        return $res;
    }

    protected function _getUserInfo($id){
        $model = new User();
        $res = $model->getOne(['status'=>1,'id'=>(int)$id]);
        return !empty($res) ? findDataToArray($res) : [];
    }

    protected function _getTaskInfo($id){
        $model = new TaskList();
        $res = $model->getOne(['state'=>1,'id'=>(int)$id]);
        return !empty($res) ? findDataToArray($res) : [];
    }
}