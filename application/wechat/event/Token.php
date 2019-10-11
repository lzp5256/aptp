<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:51 PM
 */
namespace app\wechat\event;

use app\base\controller\Base;
use app\helper\helper;
use app\model\User as UserModel;
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
                $updateData = [
                    'session_key'=>$this->data['wechat']['session_key'],
                ];
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
                $this->data['user']['uid'] = (int)$userModel->getLastInsID();
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

        $UserModel = new UserModel();
        $UserInfo  = $UserModel->getOne(['status'=>1,'id'=>$this->data['user']['uid']]);
        if(empty($UserInfo)){
            return $this->setReturnMsg('105');
        }
        $Result['data']['user'] = findDataToArray($UserInfo);
        $this->_sendEmail(findDataToArray($UserInfo));
        return $Result;
    }

    public function getWechatAccessToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type='.config('wechat')['client_grant_type'].'&appid='.config('wechat')['appid'].'&secret='.config('wechat')['secret'];
        $res = sendCurlRequest($url);
        return $res;
    }

    public function getSendMessageRes(){
        $Result =[
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        $url = config('wechat')['url']['sendTemplateMessage'].$this->data['data']['access_token'];
        $data = [
            'access_token'  => $this->data['data']['access_token'], // 接口调用凭证
            'touser'        => $this->data['data']['open_id'],      // 接收者（用户）的 openid
            'template_id'   => $this->data['data']['template_id'],  // 模板消息的id
            'page'          => $this->data['data']['redirect'],     // 跳转页面
            'form_id'       => $this->data['data']['form_id'],      // 模版id
            'data'          => $this->data['data']['keyWordValue'], // 模版内容
        ];
        $res = sendCurlRequest($url,json_encode($data),'POST');
        if(!empty($res) && json_decode($res,true)['errcode'] == '0'){
            return $Result;
        }else{
            $Result['errCode'] = json_decode($res,true)['errcode'];
            $Result['errMsg'] = json_decode($res,true)['errmsg'];
            return $Result;
        }
    }

    protected function _getUserData()
    {
        return [
            'name'=> '用户'.time().rand(0,100),
            'avatarUrl' => 'http://images.yipinchongke.com/logo.png',
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
            'created_at' => date('Y-m-d H:i:s'),
            'status' => '1',
        ];
    }

    protected function _sendEmail($user){
        $helper = new helper();
        // 拼接内容
        $title = date('Y-m-d H:i:s')."用户登录";
        $content = "【ID:".$user['id']."】【昵称：".$user['name']."】的用户在." .date('Y-m-d H:i:s')."添加登录成功！\t";
        $res = $helper->SendEmail($title,$content);
        if($res != '1'){
            writeLog(getWriteLogInfo('邮件异常','title:'.$title,'content:'.$content,'error'));
        }
    }
}