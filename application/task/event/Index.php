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
        $res = $model->selectTask(
            ['type'=>1,'status'=>'1'],
            0,10,
            'id,title,content,completables,integral,bindtap,bindtitle,open_type,created_at',
            'id ASC'
        );
        if(empty($res)){
            $Result['data'] = [];
            return $Result;
        }

        $Result['data'] = $res->toArray();
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


}