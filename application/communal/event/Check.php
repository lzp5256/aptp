<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/5
 * Time: 3:06 PM
 */
namespace app\communal\event;

class Check
{
    protected $data = [];

    public function CheckParam($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];

        if(empty($params['uid'])){
            $Result['errCode'] = 'L10035';
            $Result['errMsg'] = '抱歉,用户数据异常,请稍后再试！';
        }
        $this->data['param']['uid'] = (int)$params['uid'];

        if(empty($params['content'])){
            $Result['errCode'] = 'L10034';
            $Result['errMsg'] = '抱歉,邮件内容不能为空！';
        }
        $this->data['param']['content'] = (string) $params['content'];

        $Result['data'] = $this->data;
        return $Result;
    }
}