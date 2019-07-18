<?php
namespace app\event;

use app\base\controller\Base;

class WelfareCheck extends Base
{
    public function checkWlParams($params){
        $res = [
            'errCode' => '200',
            'errMsg'  => '',
            'data'    => [],
        ];
        $this->data['param_list']['p'] = $params['p'] ?? 1;

        $res['data'] = $this->data;
        return $res;
    }
}