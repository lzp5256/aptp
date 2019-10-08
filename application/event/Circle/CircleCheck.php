<?php
namespace app\event\Circle;

use app\base\controller\Base;

class CircleCheck extends Base
{
    protected $data = [];

    public function checkToCirCleListParams($params)
    {
        if(!is_array($params) || empty($params)){
            return $this->setReturnMsg('100');
        }
        if(empty($params['cid']) || !isset($params['cid'])){
            return $this->setReturnMsg('503');
        }
        $this->data['params']['cid'] = (int)$params['cid'];

        if(empty($params['type']) || !isset($params['type'])){
            return $this->setReturnMsg('600001');
        }
        $this->data['params']['type'] = (int)$params['type'];

        if(empty($params['user_id']) || !isset($params['user_id'])){
            return $this->setReturnMsg('101');
        }
        $this->data['params']['user_id'] = (int)$params['user_id'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToJoinCircleParams($params)
    {
        if(!is_array($params) || empty($params)){
            return $this->setReturnMsg('100');
        }
        if(empty($params['circle_id']) || !isset($params['circle_id'])){
            return $this->setReturnMsg('600002');
        }
        $this->data['params']['circle_id'] = (int)$params['circle_id'];

        if(empty($params['user_id']) || !isset($params['user_id'])){
            return $this->setReturnMsg('101');
        }
        $this->data['params']['user_id'] = (int)$params['user_id'];

        return $this->setReturnMsg('200',$this->data);
    }
}