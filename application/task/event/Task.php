<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/28
 * Time: 4:00 PM
 */
namespace app\task\event;

use app\base\controller\Base;
use app\task\model\Task as TaskModel;

class Task extends Base
{
    /**
     * @desc 验证任务信息 【公用方法，外部可调用】
     * @Author SaltedFish
     * @Date 2019.03.28
     * @return array
     */
    public function _checkTask()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $taskModel = new TaskModel();
        $findTaskInfo = $taskModel->findTask(['id'=>(int)$this->data['param']['tid'],'status'=>'1']);

        if(empty($findTaskInfo)){
            $Result['errCode'] = 'L10052';
            $Result['errMsg'] = '抱歉,未查询到任务信息，请联系管理员！';
            return $Result;
        }
        $Result['data']['task_list'] = $findTaskInfo->toArray();
        return $Result;
    }
}