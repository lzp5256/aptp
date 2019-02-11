<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/30
 * Time: 10:40 AM
 */
namespace app\demand\event;

class CheckParams
{
    protected $data = [];
    /**
     * @param int $uid 用户id
     * @param string $name 宠物昵称
     * @param int $type 宠物类型
     */
    public function checkParams($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        if(empty($params['uid']) || $params['uid'] <= 0){
            $Result['errCode'] = 'L10012';
            $Result['errMsg'] = '抱歉,参数[UID]不能为空！';
            return $Result;
        }
        $this->data['param']['uid'] = (int)$params['uid'];
        if(empty($params['name'])){
            $Result['errCode'] = 'L10013';
            $Result['errMsg'] = '抱歉,参数[Name]不能为空！';
            return $Result;
        }
        $this->data['param']['name'] = (string)$params['name'];
        if(empty($params['type'])){
            $Result['errCode'] = 'L10014';
            $Result['errMsg'] = '抱歉,参数[Type]不能为空！';
            return $Result;
        }
        $this->data['param']['type'] = (int)$params['type'];
        if(empty($params['sex'])){
            $Result['errCode'] = 'L10015';
            $Result['errMsg'] = '抱歉,参数[Sex]不能为空！';
            return $Result;
        }
        $this->data['param']['gender'] = (int)$params['sex'];
        if(empty($params['charge'])){
            $Result['errCode'] = 'L10016';
            $Result['errMsg'] = '抱歉,参数[Charge]不能为空！';
            return $Result;
        }
        $this->data['param']['charge'] = (int)$params['charge'];
        if(empty($params['vaccine'])){
            $Result['errCode'] = 'L10017';
            $Result['errMsg'] = '抱歉,参数[Vaccine]不能为空！';
            return $Result;
        }
        $this->data['param']['vaccine'] = (int)$params['vaccine'];
        if(empty($params['upload'])){
            $Result['errCode'] = 'L10018';
            $Result['errMsg'] = '抱歉,参数[Upload]不能为空！';
            return $Result;
        }
        $this->data['param']['upload'] = (string)serialize($params['upload']);
        if(empty($params['uname'])){
            $Result['errCode'] = 'L10019';
            $Result['errMsg'] = '抱歉,参数[Uname]不能为空！';
            return $Result;
        }
        $this->data['param']['uname'] = (string)$params['uname'];
        if(empty($params['region'])){
            $Result['errCode'] = 'L10020';
            $Result['errMsg'] = '抱歉,参数[Region]不能为空！';
            return $Result;
        }
        $this->data['param']['region'] = (string)$params['region'];
        if(empty($params['wechat'])){
            $Result['errCode'] = 'L10021';
            $Result['errMsg'] = '抱歉,参数[Wechat]不能为空！';
            return $Result;
        }
        $this->data['param']['wechat'] = (string)$params['wechat'];
        if(empty($params['phone'])){
            $Result['errCode'] = 'L10022';
            $Result['errMsg'] = '抱歉,参数[Phone]不能为空！';
            return $Result;
        }
        if(strlen($params['phone']) != 11){
            $Result['errCode'] = 'L10023';
            $Result['errMsg'] = '抱歉,输入的手机号长度错误！';
            return $Result;
        }
        if(!isMobile($params['phone'])){
            $Result['errCode'] = 'L10024';
            $Result['errMsg'] = '抱歉,请输入正确的手机号！';
            return $Result;
        }
        $this->data['param']['phone'] = (string)$params['phone'];

        if(empty($params['reason'])){
            $Result['errCode'] = 'L10025';
            $Result['errMsg'] = '抱歉,参数[Reason]不能为空！';
            return $Result;
        }
        $this->data['param']['reason'] = (string)$params['reason'];

        if(empty($params['adopt'])){
            $Result['errCode'] = 'L10026';
            $Result['errMsg'] = '抱歉,参数[Adopt]不能为空！';
            return $Result;
        }
        $this->data['param']['adopt'] = (string)$params['adopt'];

        $Result['data'] = $this->data;
        return $Result;

    }
}