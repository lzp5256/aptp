<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/22
 * Time: 4:08 PM
 */
namespace app\task\event;

use app\base\controller\RSAUtils;
use app\user\model\User as UserModel;
use app\task\model\Task as TaskModel;
use app\user\model\UserSign as UserSignModel;
use app\user\model\UserCbAccount as UserCbAccountModel;
use app\user\model\UserCbAccountChange as UserCbAccountChangeModel;

class Sign
{
    protected $Data = [];

    protected function _setData($name,$value)
    {
        $this->Data[$name] = $value;
    }

    public function handle($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        // 验证
        if(($check_res = $this->_check($data)) && $check_res['errCode']!='200'){
            return $check_res;
        }
        // 更新签到表,cb账户表，cb账户详情表
        if(($update_sign_res = $this->_updateUserSign($data)) && $update_sign_res['errCode']!='200'){
            return $update_sign_res;
        }
        if(($update_user_cb_res = $this->_updateUserCb()) && $update_user_cb_res['errCode']!='200'){
            return $update_sign_res;
        }
        if(($update_user_cb_account_res = $this->_updateUserCbAccount()) && $update_user_cb_account_res['errCode']!='200'){
            return $update_sign_res;
        }

        return $Result;

    }

    /** 验证方法 */

    protected function _check($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        // 验证token加密字符串是否正确
        if(($check_token_res = $this->_checkToken($data['param']['encrypt'])) && $check_token_res['errCode'] != '200'){
            return $check_token_res;
        }
        // 验证用户是否有效，任务id是否有效，用户此任务是否为第一次
        if(($check_user_res = $this->_checkUser($data['param']['uid'])) && $check_user_res['errCode']!='200'){
            return $check_user_res;
        }
        if(($check_task_res = $this->_checktask($data['param']['tid'])) && $check_task_res['errCode']!='200'){
            return $check_task_res;
        }
        if(($check_user_sign_res = $this->_checkSign($data['param']['uid'])) && $check_user_sign_res['errCode'] !='200'){
            return $check_user_sign_res;
        }

        return $Result;
    }

    protected function _checkToken($token)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $Rsa = new RSAUtils();
        $str = 'muyao'.date('Ymd');
        $decryptStr = $Rsa->pikeyDecrypt($token);
        if(!isset($decryptStr) || empty($decryptStr)){
            $Result['errCode'] = 'L10049';
            $Result['errMsg'] = '抱歉,数据解密失败，请联系管理员！';
            return $Result;
        }
        if($str != $decryptStr){
            $Result['errCode'] = 'L10050';
            $Result['errMsg'] = '抱歉,Token有误，请联系管理员！';
            return $Result;
        }

        return $Result;
    }

    protected function _checkUser($uid)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $userModel = new UserModel();
        $findUserInfo = $userModel->findUser(['id'=>(int)$uid,'status'=>'1'])->toArray();

        if(empty($findUserInfo)){
            $Result['errCode'] = 'L10051';
            $Result['errMsg'] = '抱歉,未查询到用户信息，请联系管理员！';
            return $Result;
        }
        $this->_setData('user_info',$findUserInfo); //储存用户信息
        return $Result;
    }

    protected function _checkTask($tid)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $taskModel = new TaskModel();
        $findTaskInfo = $taskModel->findTask(['id'=>(int)$tid,'status'=>'1'])->toArray();

        if(empty($findTaskInfo)){
            $Result['errCode'] = 'L10052';
            $Result['errMsg'] = '抱歉,未查询到任务信息，请联系管理员！';
            return $Result;
        }
        $this->_setData('task',$findTaskInfo); //储存任务信息
        return $Result;
    }

    protected function _checkSign($uid)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $userSignModel = new UserSignModel();
        $findUserSignInfo = $userSignModel->findSign(['uid'=>(int)$uid,'status'=>'1']);

        if(!empty($findUserSignInfo) && (date('Y-m-d',strtotime($findUserSignInfo->last_sign_time)) == date('Y-m-d'))){
            $Result['errCode'] = 'L10053';
            $Result['errMsg'] = '抱歉,您今日已签到！';
            return $Result;
        }
        if(!empty($findUserSignInfo)){
            $this->_setData('sign',['id'=>$findUserSignInfo,'last_sign_time'=>$findUserSignInfo->last_sign_time,'uid'=>$findUserSignInfo->uid]); //储存用户签到信息
        }

        return $Result;

    }

    /** 处理方法 */

    protected function _updateUserSign($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $arr  = $this->Data;
        // 查询是否存在此用户签到信息，新用户签到新增，老用户签到增加次数
        $userSignModel = new UserSignModel();
        $findUserSignInfo = $userSignModel->findSign(['uid'=>(int)$arr['user_info']['id'],'status'=>'1']);

        if(empty($findUserSignInfo)){
            // 新增
            $userSignModel -> uid = $arr['user_info']['id'];
            $userSignModel -> last_sign_time = date('Y-m-d H:i:s');
            $userSignModel -> created_at = date('Y-m-d H:i:s');
            $userSignModel ->status = '1';
            $userSignModel ->total_data = '1';
            $userSignModel -> save();
            $sign_id = $userSignModel->id;
            $this->_setData('sign',['id'=>$sign_id]);
        }else{
            // 更新
            $updateRes = $userSignModel->updateUser(
                [
                    'id'=>(int)$findUserSignInfo->id,
                    'status'=>'1'
                ],
                [
                    'total_data' => $findUserSignInfo->total_data + 1,
                    'last_sign_time' => date('Y-m-d H:i:s'),
                ]
            );
            if(!$updateRes){
                $Result['errCode'] = 'L10054';
                $Result['errMsg'] = '抱歉,签到记录更新失败！';
                return $Result;
            }
            $this->_setData('sign',['id'=>$findUserSignInfo->id]);
        }
        return $Result;
    }

    protected function _updateUserCb()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $arr  = $this->Data;
        // 查询是否存在此用户签到信息，新用户签到新增，老用户签到增加次数
        $userCbAccountModel = new UserCbAccountModel();
        $findUserCbAccountInfo = $userCbAccountModel->findUserCbAccount(['uid'=>(int)$arr['user_info']['id'],'status'=>'1']);

        if(empty($findUserCbAccountInfo)){
            // 新增
            $userCbAccountModel -> uid = (int)$arr['user_info']['id'];
            $userCbAccountModel -> num = (string)$arr['task']['integral'];
            $userCbAccountModel -> created_at = date('Y-m-d H:i:s');
            $userCbAccountModel -> status = '1';
            $userCbAccountModel -> use_num = '0';
            $userCbAccountModel -> save();
            $this->_setData('user_cb_account',['id'=>$userCbAccountModel->id]);
        }else{
            // 更新
            $updateRes = $userCbAccountModel->updateUserCbAccount(
                [
                    'id'=>(int)$findUserCbAccountInfo['id'],
                    'status'=>'1',
                    'uid' => (int)$arr['user_info']['id'],
                ],
                [
                    'num' => $findUserCbAccountInfo['num'] + $arr['task']['integral'],
                ]
            );
            if(!$updateRes){
                $Result['errCode'] = 'L10055';
                $Result['errMsg'] = '抱歉,账户更新失败！';
                return $Result;
            }
            $this->_setData('user_cb_account',['id'=>$findUserCbAccountInfo->id]);
        }
        return $Result;
    }

    protected function _updateUserCbAccount()
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $arr  = $this->Data;

        // 查询是否存在此用户签到信息，新用户签到新增，老用户签到增加次数
        $UserCbAccountChangeModel = new UserCbAccountChangeModel();

        // 新增
        $UserCbAccountChangeModel -> uid = (int)$arr['user_info']['id'];
        $UserCbAccountChangeModel -> uca_id = (int)$arr['user_cb_account']['id'];
        $UserCbAccountChangeModel -> type = (string)'1';
        $UserCbAccountChangeModel -> cb_id = (int)$arr['task']['id'];
        $UserCbAccountChangeModel -> num = (string)$arr['task']['integral'];
        $UserCbAccountChangeModel -> status = '1';
        $UserCbAccountChangeModel -> created_at = date('Y-m-d H:i:s');
        $res = $UserCbAccountChangeModel -> save();
        if(!$res){
            $Result['errCode'] = 'L10056';
            $Result['errMsg'] = '抱歉,账户详情更新失败！';
            return $Result;
        }
        $this->_setData('user_cb_account',['id'=>$UserCbAccountChangeModel->id]);

        return $Result;
    }


}