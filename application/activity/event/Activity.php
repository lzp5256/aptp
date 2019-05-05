<?php
namespace app\activity\event;

use app\base\controller\Base;
use app\activity\model\Activity as ActivityModel;

class Activity extends Base
{
    public function getActivityListOfEvent(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $page = $this->data['page']; // 页数
        $num  = '10';  // 每页数量
        $model = new ActivityModel();
        if(!($res = $model->getActivityPageList(['status'=>1],$page,$num,'id,img'))){
            $Res['errCode'] = '10101';
            $Res['errMsg']  = '列表获取失败';
            return $Res;
        }
        $Res['data'] = selectDataToArray($res);
        return $Res;
    }
}