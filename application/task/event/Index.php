<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/26
 * Time: 12:26 AM
 */
namespace app\task\event;

use app\base\controller\Base;
use app\task\model\Task;
use app\user\model\UserCbAccount;
use app\exchange\model\Exchange;
use app\user\model\UserCbAccountChange;

class Index extends Base
{
    public function handle()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        // 获取账户信息
        if(($getUserCbAccountInfoRes = $this->_getUserCbAccountInfo()) && $getUserCbAccountInfoRes['errCode'] != '200'){
            return $getUserCbAccountInfoRes;
        }
        // 获取任务列表
        if(($getTaskListRes = $this->_getTaskList()) && $getTaskListRes['errCode'] != '200'){
            return $getTaskListRes;
        }
        // 获取奖励列表
        if(($getExchangeListRes = $this->_getExchangeList()) && $getExchangeListRes['errCode'] != '200'){
            return $getExchangeListRes;
        }

        $Result['data'] = [
            'user_cb_account_list' => $getUserCbAccountInfoRes['data'],
            'task_list' => $getTaskListRes['data'],
            'exchange_list' => $getExchangeListRes['data'],
        ];

        return $Result;

    }

    protected function _getUserCbAccountInfo()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        $model = new UserCbAccount();
        $res = $model->findUserCbAccount(['uid'=>(int)$this->data['param']['uid'],'status'=>'1'],'id,uid,num,created_at');
        if(empty($res)){
            $Result['data'] = [];
            return $Result;
        }
        $Result['data'] = $res->toArray();
        return $Result;
    }

    protected function _getTaskList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        $model = new Task();
        $res = $model->selectTask(['type'=>1,'status'=>'1'],0,10,'*','id ASC');
        if(empty($res)){
            $Result['data'] = [];
            return $Result;
        }
        // 查询每个任务的完成度
        $this->data['task_list']= $res->toArray();
        $taskDegree = $this->_getTaskCompletionDegree();

        foreach ($this->data['task_list'] as $k => $v){
            $this->data['task_list'][$k]['completable_status'] = '2';
            $this->data['task_list'][$k]['completable_status_str'] = $v['bindtitle'];
            $this->data['task_list'][$k]['completable'] = 0;
            $this->data['task_list'][$k]['disabled'] = 'false';
            if(in_array($v['id'],array_keys($taskDegree))){
                $this->data['task_list'][$k]['completable_status'] = $taskDegree[$v['id']]['completable_status'];
                $this->data['task_list'][$k]['completable_status_str'] = $taskDegree[$v['id']]['completable_status_str'];
                $this->data['task_list'][$k]['completable'] = $taskDegree[$v['id']]['completable'];
                $this->data['task_list'][$k]['disabled'] = $taskDegree[$v['id']]['disabled'];
            }

        }
        $Result['data'] = $this->data['task_list'];
        return $Result;
    }

    protected function _getExchangeList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        $model = new Exchange();
        $res = $model->selectExchange(['type'=>1,'status'=>'1'],0,10,'*','id ASC');
        if(empty($res)){
            $Result['data'] = [];
            return $Result;
        }
        $Result['data'] = $res->toArray();
        return $Result;
    }

    protected function _getTaskCompletionDegree()
    {
        $data = $tid = [];
        foreach ($this->data['task_list'] as $k => $v){
            $tid[] = $v['id'];
        }
        $tid_unique = array_unique($tid);
        $model = new  UserCbAccountChange();
        $res = $model->selectUserCbAccountChange(
                [
                    'uid'=>$this->data['param']['uid'],
                    'status'=>'1',
                    'cb_id'=>['IN',$tid_unique],
                    'type' => '1',
                    'created_at' => ['gt',date('Y-m-d',time())]
                ], 0,100
        );
        if(count($res)<=0){
            $res = [];
            return $res;
        }
        foreach ($res->toArray() as $k => $v){
            $data[$v['cb_id']] = [
                'completable_status' => '1',
                'completable_status_str' => '已完成',
                'completable' => '1',
                'disabled' => 'true',
            ];
        }
        return $data;
    }




}