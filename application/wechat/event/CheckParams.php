<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:59 PM
 */
namespace app\wechat\event;

class CheckParams
{
    /**
     * 验证获取用户微信token | post参数
     * @param $params
     * @author lizhipeng
     * @date 2019/01/08
     * @return array
     */
    public function checkGetTokenParams($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => []
        ];
        if(empty($params['code'])){
            $Result['errCode'] = 'L10003';
            $Result['errMsg'] = '抱歉,用户登录凭证不能为空！';
            return $Result;
        }

        $Result['data']['params']['code'] = (string) $params['code'];
        $Result['data']['params']['current_time'] = date('Y-m-d H:i:s');

        return $Result;
    }
}