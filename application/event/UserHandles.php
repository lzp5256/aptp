<?php
namespace app\event;

use app\base\controller\Base;
use app\user\event\User;
use app\model\User as UserModel;
use app\helper\helper;

class UserHandles extends Base
{
    public function handleToUserInfoRes()
    {
        $UserModel = new UserModel();
        $UserInfo  = $UserModel->getOne(['status'=>1,'id'=>$this->data['param']['uid']]);
        if(empty($UserInfo)){
            return $this->setReturnMsg('105');
        }
        $info = findDataToArray($UserInfo);
        return $this->setReturnMsg('200',$info);
    }




}
