<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:34 PM
 */
namespace app\event;

use app\base\controller\Base;
use app\model\AskUser as AskUserModel;

class AskUser extends Base {

    public function getAllUserList(){
        $uid = $this->data['uid'];
        $where['state'] = 1;
        $where['uid'] = ['IN',$uid];
        $model = new AskUserModel();
        $data = $model->selectAskUser($where,0,count($uid));
        if(empty($data)){
            return [];
        }
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$v['uid']] = [
                'name' => $v['name'],
            ];
        }
        return $arr;
    }

    public function checkUser(){
        $id = $this->data['uid'];
        $AUModel = new AskUserModel();
        $user = $AUModel->findAskUser(['uid'=>$id,'state'=>1]);
        if (empty($user)){
            return [];
        }
        return findDataToArray($user);
    }

}













