<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/22
 * Time: 4:04 PM
 */
namespace app\task\event;

use app\task\model\Task;
class Check
{
    public $data = [];

    public function checkParams($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        if(empty($params['uid'])){
            $Result['errCode'] = 'L10046';
            $Result['errMsg'] = '抱歉,UID不能为空,请联系管理人员';
            return $Result;
        }
        $this->data['param']['uid'] = $params['uid'];
        if(empty($params['encrypt'])){
            $Result['errCode'] = 'L10047';
            $Result['errMsg'] = '抱歉,Token不能为空,请联系管理人员';
            return $Result;
        }
        $this->data['param']['encrypt'] = $params['encrypt'];
        if (empty($params['tid'])){
            $Result['errCode'] = 'L10048';
            $Result['errMsg'] = '抱歉,TID不能为空,请联系管理人员';
            return $Result;
        }
        $this->data['param']['tid'] = $params['tid'];

        $Result['data'] = $this->data;
        return $Result;

    }

    public function checkIndexParams($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        if(empty($params['uid'])){
            $Result['errCode'] = 'L10057';
            $Result['errMsg'] = '抱歉,UID不能为空,请联系管理人员';
            return $Result;
        }
        $this->data['param']['uid'] = (int)$params['uid'];
        $Result['data']=$this->data;
        return $Result;
    }
}