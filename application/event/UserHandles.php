<?php
namespace app\event;

use app\base\controller\Base;
use app\user\event\User;
use app\model\User as UserModel;
use app\helper\helper;
use think\Db;
use think\Exception;

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

    // 处理编辑操作
    // Author: lizhipeng
    // Date: 2019.09.04
    // Return: array()
    public function handleToEditRes()
    {
        try{
            $helper = new helper();
            $user_model = new UserModel();
            $update_data = [
                'name' => (string)trim($this->data['param']['name']),
                'sex'  => (int)$this->data['param']['sex'],
                'head_portrait_url' => (string)trim($this->data['param']['img'])
            ];
            $edit_res = $user_model->toUpdate(['status'=>1,'id'=>(int)$this->data['user_info']['id']],$update_data);
            if(!$edit_res){
                $helper->SendEmail(
                    "用户【".$this->data['user_info']['name']."】【".date('Y-m-d H:i:s')."】编辑个人资料失败",
                    "用户ID为\t【".$this->data['param']['uid']."】的用户在." .date('Y-m-d H:i:s')."编辑个人资料失败，请尽快处理！\t"
                    );
                return $this->setReturnMsg('103');
            }
            $helper->SendEmail(
                "用户【".$this->data['user_info']['name']."】在【".date('Y-m-d H:i:s')."】编辑个人资料",
                "用户ID为\t【".$this->data['param']['uid']."】的用户在." .date('Y-m-d H:i:s')."编辑个人资料！\t"
            );
            return $this->setReturnMsg('200');
        } catch (Exception $e){
            $helper->SendEmail(
                "用户【".$this->data['user_info']['name']."】【".date('Y-m-d H:i:s')."】编辑个人资料异常",
                "用户ID为:【".$this->data['user_info']['id']."】异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }


}
