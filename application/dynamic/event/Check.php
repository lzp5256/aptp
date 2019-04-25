<?php
namespace app\dynamic\event;

use app\base\controller\Base;

class Check extends Base
{
    public function checkDynamicInfoParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];

        if(empty($param['uid'])){
            $Result['errCode'] = 'L10089';
            $Result['errMsg'] = '抱歉,系统异常,请联系管理员';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['did'])){
            $Result['errCode'] = 'L10079';
            $Result['errMsg'] = '抱歉,系统异常,请联系管理员';
            return $Result;
        }
        $this->data['param']['did'] = $param['did'];

        $Result['data'] = $this->data;
        return $Result;
    }
}
