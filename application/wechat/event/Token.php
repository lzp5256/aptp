<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:51 PM
 */
namespace app\wechat\event;

use app\base\controller\Base;
use app\user\model\User;
use app\wechat\model\Token as TokenModel;
use think\Db;
use think\Exception;

class Token extends Base
{
    /**
     * 通过 wx.login() 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录。
     * Demo:GET https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
     */
    public function getWechatToken()
    {
        $Result =[
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $wechatData = sendCurlRequest("https://api.weixin.qq.com/sns/jscode2session?appid=".config('wechat')['appid']."&secret=".
            config('wechat')['secret']."&js_code=".$this->data['params']['code']. "&grant_type=".config('wechat')['grant_type']);

        $result = json_decode($wechatData,true);
        if(isset($result['errcode']) && !empty($wechatData)){
            $Result['errCode'] = 'L10004';
            $Result['errMsg'] = '抱歉,获取微信登录凭证失败,请稍后重试！';
            return $Result;
        }

        $this->data['wechat']['openid'] = $result['openid'];
        $this->data['wechat']['session_key'] = $result['session_key'];

        // 添加一条用户数据或更新用户session_key
        $userModel = new User();
        $findUser =  $userModel->findUser([
            'openid'=>$this->data['wechat']['openid'],
            'status' => 1
        ]);
        // 开启事务
        Db::startTrans();
        try{
            // 如果用户存在，则更新seesion_key
            // 如果用户不存在，新增用户信息
            if($findUser){
                $updateWhere['id'] = $findUser['id'];
                $updateData = ['session_key'=>$this->data['wechat']['session_key']];
                $updateUser = $userModel->saveUser($updateWhere,$updateData);
                if(!$updateUser){
                    DB::rollback();
                }
                $this->data['user']['uid'] = (int)$findUser['id'];
            }else{
                $userData = $this->_getUserData();
                $addUser = $userModel->addUser($userData);
                if(!$addUser){
                    DB::rollback();
                }
                $this->data['user']['uid'] = (int)$addUser;
            }
            $tokenData = $this->_getTokenData();
            $tokenModel = new TokenModel();
            $addToken = $tokenModel->addToken($tokenData);
            if($addToken){
                DB::commit();
            }

        }catch (Exception $e){
            DB::rollback();
            return $e->getMessage();
        }

        // 返回token，用于每次访问的参数
        $Result['data'] = $tokenData['token'];
        return $Result;
    }

    protected function _getUserData()
    {
        return [
            'openid' => $this->data['wechat']['openid'],
            'session_key' => $this->data['wechat']['session_key'],
            'status' => '1',
            'created_at' => $this->data['params']['current_time']
        ];
    }

    protected function _getTokenData()
    {
        return [
            'uid' => $this->data['user']['uid'],
            'openid' => $this->data['wechat']['openid'],
            'token' => parent::encryption($this->data['wechat']['session_key']),
            'stime' => date('Y-m-d H:i:s'),
            'etime' => date('Y-m-d H:i:s',strtotime('+7 day')),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => '1',
        ];
    }
}