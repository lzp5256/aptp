<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/12
 * Time: 4:18 PM
 */
namespace app\index\event;

use app\demand\model\Demand as DemandModel;
use app\user\model\User as UserModel;
class Index
{
    public function getReList($param)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new DemandModel();
        $data = $model->selectDemand(true,$param['page'],5);
        if(count($data)<=0){
            $Result['errCode'] = 'L10029';
            $Result['errMsg'] = '抱歉，暂无送养数据！';
            return json($Result);
        }
        $arr = collection($data)->toArray();
        foreach ($arr as $k=>$v){
            $repeat_uids[] = $v['uid'];
        }
        $new_uids = array_unique($repeat_uids);
        $userModel =new UserModel();
        $userData = $userModel->selectUser(['status'=>'1','id'=>['IN',$new_uids]],0,count($new_uids));
        if(count($userData)<=0){
            $Result['errCode'] = 'L10030';
            $Result['errMsg'] = '抱歉，暂无用户数据！';
            return json($Result);
        }
        foreach ($userData as $k => $v){
            $user[$v->id] = $v->name;
        }
        foreach ($arr as $k => $v){
            $arr[$k]['upload'] = unserialize($v['upload']);
            $arr[$k]['uname'] = $user[$v['uid']];
        }
        $Result['data']=$arr;
        return $Result;
    }
}