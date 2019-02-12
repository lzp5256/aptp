<?php
namespace app\index\controller;

use app\base\controller\Base;
use app\demand\model\Demand;


class Index extends Base
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function getReList()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $params = request()->param();
        $model = new Demand();
        $data = $model->selectDemand(true,0,10);
        if(count($data)<=0){
            $Result['errCode'] = 'L10029';
            $Result['errMsg'] = '抱歉，暂无数据！';
        }else{
            $Result['data'] = collection($data)->toArray();
        }
        return json($Result);
    }


}
