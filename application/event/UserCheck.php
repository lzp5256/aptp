<?php
namespace app\event;

use app\base\controller\Base;
use app\model\User;
use app\model\UserLikes;

class UserCheck extends Base
{
    protected $data = [];



    public function checkToUserInfoParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        return $this->setReturnMsg('200',$this->data);

    }


}