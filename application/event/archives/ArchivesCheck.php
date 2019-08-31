<?php
namespace app\event\archives;

use app\base\controller\Base;
use app\model\User;

class ArchivesCheck extends Base
{
    public function checkToCreate($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('400001');
        }
        $user_model = new User();
        $getUserInfo = $user_model->getOne(['status' => 1 ,'id'=>$param['uid']]);
        if(empty($getUserInfo)){
            return $this->setReturnMsg('105');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['src'])){
            return $this->setReturnMsg('500002');
        }
        $this->data['param']['src'] = (string)trim($param['src']);

        if(empty($param['name']) || !isset($param['name'])){
            return $this->setReturnMsg('500001');
        }
        $this->data['param']['name'] = (string)trim($param['name']);

        if(empty($param['sex']) || !isset($param['sex']) || in_array($param['sex'],[1,2])){
            return $this->setReturnMsg('500003');
        }
        $this->data['param']['sex'] = (int)trim($param['sex']);

        if(empty($param['weight']) || !isset($param['weight']) || $param['weight'] < 0){
            return $this->setReturnMsg('500004');
        }
        $this->data['param']['weight'] = (int)trim($param['weight']);

        if(empty($param['sterilization']) || !isset($param['sterilization']) || in_array($param['sex'],[1,2])){
            return $this->setReturnMsg('500005');
        }
        $this->data['param']['weight'] = (int)trim($param['weight']);


        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToList($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $user_model = new User();
        $getUserInfo = $user_model->getOne(['status' => 1 ,'id'=>$param['uid']]);
        if(empty($getUserInfo)){
            return $this->setReturnMsg('105');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        return $this->setReturnMsg('200',$this->data);
    }
}
