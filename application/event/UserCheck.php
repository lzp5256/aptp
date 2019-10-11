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

    /**
     *  uid: 16
        avatar_url: https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKvEFUUhmicMJVARZicC9ApzqvlFbSibsX1Nc4nibhWPJ2xGia4wThpS8AViaRxWa4nicYcZSB3HXkPick4gg/132
        name: Zhi.L
        sex: 1
        raw_data: {"nickName":"Zhi.L","gender":1,"language":"zh_CN","city":"","province":"Shanghai","country":"China","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKvEFUUhmicMJVARZicC9ApzqvlFbSibsX1Nc4nibhWPJ2xGia4wThpS8AViaRxWa4nicYcZSB3HXkPick4gg/132"}
     * @param $param
     * @return array
     */
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

        if(empty($param['avatar_url'])){
            return $this->setReturnMsg('00043');
        }
        $this->data['param']['avatar_url'] = (string)trim($param['avatar_url']);

        if (empty($param['name'])){
            return $this->setReturnMsg('00044');
        }
        $this->data['param']['name'] = (string)trim($param['name']);

        if (!isset($param['sex']) || !in_array($param['sex'],[0,1,2])){
            return $this->setReturnMsg('00045');
        }
        $this->data['param']['sex'] = (int)$param['sex'];

        if (empty($param['raw_data'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['raw_data'] = (string)$param['raw_data'];

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

        if(empty($param['target']) || !isset($param['target']) || $param['target'] == 'undefined'){
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

    public function checkToFollowListParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }
        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['type']) || !isset($param['type']) || !in_array($param['type'],[1,2])){ // 1为我关注的 2为关注我的
            return $this->setReturnMsg('100');
        }
        $this->data['param']['type'] = (int)$param['type'];

        return $this->setReturnMsg('200',$this->data);
    }

}