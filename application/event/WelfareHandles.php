<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Welfare;


class WelfareHandles extends Base
{
    public function handleWlRes()
    {
        $res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new Welfare();
        if(!($returnData = $model->getAll(['state'=>1],0,20))){
            return $this->setReturnMsg('300002');
        }
        $res['data'] = $returnData;
        return $res;
    }
}