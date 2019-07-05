<?php
namespace app\controllers\controller;

use app\base\controller\Base;
use app\model\Rc as RcModel;
use app\event\RcCheck;
use app\event\RcHandles;

class Rc extends Base
{
    public function ra(){
        $res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $params = request()->post();
        if(empty($params['rc_name'])){
            $res['errCode'] = '0';
            $res['errMsg']  = '名称不能为空';
            return json($res);
        }
        if(empty($params['rc_type'])){
            $res['errCode'] = '0';
            $res['errMsg']  = '类型不能为空';
            return json($res);
        }
        if(!in_array($params['hot'],[0,1])){
            $res['errCode'] = '0';
            $res['errMsg']  = '请选择是否热门';
            return json($res);
        }
        $data = [
            're_id'=>0,
            'rc_name'=>(string)trim($params['rc_name']),
            'rc_type'=>(int)$params['rc_type'],
            'hot'=>(int)$params['hot'],
            'state'=>1,
            'created_at'=>date('Y-m-d H:i:s'),
        ];
        $model = new RcModel();
        if($get =$model->getOne(['rc_name'=>$params['rc_name'],'state'=>1]) ){
            $res['errCode'] = '0';
            $res['errMsg']  = '已存在';
            return json($res);
        }
        if(!($add = $model->toAdd($data))){
            $res['errCode'] = '0';
            $res['errMsg']  = '添加失败';
            return json($res);
        }
        return json($res);
    }

    public function rs(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '查询成功',
            'data'    => [],
        ];
        $param = request()->post();

        $checkEvent  = new RcCheck();
        $handleEvent = new RcHandles();

        if(($checkRes = $checkEvent->setData($param)->checkRsParams()) && $checkRes['errCode'] != '200'){
            return json($checkRes);
        }

        if(($handlesRes = $handleEvent->setData($checkRes['data'])->handlesRsRes()) && $handlesRes['errCode'] != '200'){
            return json($handlesRes);
        }

        $res['data'] = $handlesRes['data'];
        return json($res);
    }
}
