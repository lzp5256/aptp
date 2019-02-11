<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/29
 * Time: 11:40 AM
 */
namespace app\demand\event;

use app\base\controller\Base;
use think\Exception;
use app\demand\model\Demand as DemandModel;

class Demand
{
    public function handle($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $saveData = $this->_getSaveData($data);
        try{
            $model = new DemandModel();
            $res = $model->addDemand($saveData);
            if(!$res){
                $Result['errCode'] = 'L10027';
                $Result['errMsg'] = '添加失败！';
                return $Result;
            }
        }catch (Exception $e){
            $Result['errCode'] = 'L10028';
            $Result['errMsg'] = $e->getMessage();
            return $Result;
        }
        return $Result;
    }

    /**
     * @desc 重置参数
     * @param $data
     * @return mixed
     */
    public function _getSaveData($data)
    {
        foreach ($data['param'] as $k => $v){
            $arr[$k] = $v;
        }
        $arr['status'] = '1';
        $arr['created_at'] = date('Y-m-d H:i:s');
        return $arr;
    }

}