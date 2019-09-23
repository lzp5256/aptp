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

    public function checkToEditParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];
        $user_model = new User();
        $getUserInfo = $user_model->getOne(['status' => 1 ,'id'=>$param['uid']]);
        if(empty($getUserInfo)){
            return $this->setReturnMsg('105');
        }
        $this->data['user_info'] = findDataToArray($getUserInfo);

        if(empty($param['imgList'])){
            return $this->setReturnMsg('00043');
        }
        $this->data['param']['img'] = (string)trim($param['imgList']);

        if (empty($param['name'])){
            return $this->setReturnMsg('00044');
        }
        $this->data['param']['name'] = (string)trim($param['name']);

        if (empty($param['sex']) || !in_array($param['sex'],[1,2])){
            return $this->setReturnMsg('00045');
        }
        $this->data['param']['sex'] = (int)$param['sex'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToFollowParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['target']) || !isset($param['target'])){
            return $this->setReturnMsg('700001');
        }
        $this->data['param']['target'] = (int)$param['target'];

        if((int)$param['uid'] == (int)$param['target']){
            return $this->setReturnMsg('700004');
        }

        if(empty($param['type']) || !isset($param['type'])){
            return $this->setReturnMsg('700002');
        }
        $this->data['param']['type'] = (int)$param['type'];

        return $this->setReturnMsg('200',$this->data);
    }

}