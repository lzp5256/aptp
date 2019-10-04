<?php
namespace app\event\Message;

use app\base\controller\Base;

class MessageCheck extends Base
{
    public function checkToMessageList($params)
    {
        if(!is_array($params) || empty($params)){
            return $this->setReturnMsg('100');
        }
        if(empty($params['uid']) || !isset($params['uid']) || $params['uid'] == 'undefined'){
            return $this->setReturnMsg('105');
        }
        $this->data['params']['uid'] = (int)$params['uid'];

        return $this->setReturnMsg('200',$this->data);
    }
}