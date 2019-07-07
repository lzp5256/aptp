<?php
namespace app\event;

use app\base\controller\Base;
use app\helper\helper;
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

    public function handlesRiRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $helper = new helper();
        $model = new Rc();
        if(!($returnData = $model->getOne(['state'=>1,'rc_name'=>$this->data['v']]))){
            return $this->setReturnMsg('300002');
        }
        $returnData['type'] = $helper->RcType($returnData['rc_type'])['name'];
        $returnData['src']  = $helper->RcType($returnData['rc_type'])['src'];
        $returnData['describe'] = $helper->RcType($returnData['rc_type'])['describe'];
        $res['data'] = $returnData;
        return $res;
    }
}
