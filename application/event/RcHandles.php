<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Rc;

class RcHandles extends Base
{
    public function handlesRsRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new Rc();
        if(!($returnData = $model->getAll(['state'=>1,'rc_name'=>['like',"%".$this->data['v']."%"]],0,10))){
            return $this->setReturnMsg('300002');
        }
        $res['data'] = $returnData;
        return $res;
    }
}
