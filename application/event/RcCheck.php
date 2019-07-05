<?php
namespace app\event;

use app\base\controller\Base;

class RcCheck extends Base
{
    public function checkRsParams(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '',
            'data'    => [],
        ];
        if(empty($this->data['v'])){
            return $this->setReturnMsg('300001');
        }
        $res['data'] = $this->data;
        return $res;
    }
}
