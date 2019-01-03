<?php
/**
 * 微信相关操作
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:38 PM
 */

namespace app\wechat\controller;

class Token
{

    public function __construct()
    {
        // 判断是否是post请求
        if(request()->isPost() != true){
            return 'request is not post';
        }
    }

    /**
     * 登录凭证校验。
     * 通过 wx.login() 接口获得临时登录凭证 code 后传到开发者服务器调用此接口完成登录。
     * Demo:GET https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
     * Author:sfw
     * Date:2019/01/03
     */
    public function getWechatToken()
    {
        // echo 'hello wechat';
        $param = request()->post('post.');
        vp($param);die;
    }
}