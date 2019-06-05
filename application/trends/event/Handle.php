<?php
namespace app\trends\event;

use app\base\controller\Base;
use app\helper\message;
use app\trends\model\TrendsList;

class Handle extends Base
{
    public function HandleTrendsReleaseRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $save_data = $this->_getHandleTrendsReleaseData();
        $trends_list_model = new TrendsList();

        if(!($add_res = $trends_list_model->getAddTrendsRes($save_data))){
            $Res['errCode'] = '00034';
            $Res['errMsg'] = message::$message['00034'];
            return $Res;
        }

        return $Res;
    }

    protected function _getHandleTrendsReleaseData(){
        return [
            'uid'     => (int)$this->data['param_list']['uid'],
            'title'   => (string)$this->data['param_list']['title'],
            'content' => (string)$this->data['param_list']['content'],
            'src'     => (string)$this->data['param_list']['src'],
            'likes'   => 0,
            'state'   => 1,
            'cretaed_at' => date('Y-m-d H:i:s'),
        ];
    }
}