<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:12 PM
 */
namespace app\base\controller;

use app\wechat\model\Token;

class Base
{
    // 储存数据
    protected $data = [];

    public function __construct()
    {
        $this->check();
    }

    public function check()
    {
        // 判断是否是post请求
        if(request()->isPost() != true){
            return "METHOD ERROR";
        }

        // 验证token
        $token = request()->post('token');
        $uid = request()->post('uid');

        $model = new Token();
        $res = $model->findToken(['token'=>$token,'uid'=>$uid]);

        if(empty($res->toArray())){
            return 'Token ERROR';
        }

        return true;
    }

    public function setData($setData)
    {
        $this->data = $setData;
        return $this;
    }

    /**
     * 生成用户token
     * @param $data 用户session_key
     * @return string
     */
    public function encryption($data)
    {
        return md5($data.rand(0,9).time());
    }


}