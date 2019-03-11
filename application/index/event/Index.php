<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/12
 * Time: 4:18 PM
 */
namespace app\index\event;

use app\demand\model\Demand as DemandModel;
use app\region\model\Region;
use app\user\model\User as UserModel;
class Index
{
    /**
     * @desc 获取首页列表
     * @param int  $param['page']  查询页数
     * @return array
     */
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
            $Result['errMsg'] = '抱歉，暂无更多数据！';
            return $Result;
        }
        $arr = collection($data)->toArray();
        foreach ($arr as $k=>$v){
            $repeat_uids[] = $v['uid'];
            $region_ids[] = $v['region'];
        }
        $new_uids = array_unique($repeat_uids);
        $r_ids = array_unique($region_ids);
        $userModel =new UserModel();
        $userData = $userModel->selectUser(['status'=>'1','id'=>['IN',$new_uids]],0,count($new_uids));
        $regionModel = new Region();
        $selectRegion =  $regionModel->selectRegion(['status'=>'1','rg_id'=>['IN',$r_ids]],0,count($r_ids));
        foreach ($selectRegion as $k => $v){
            $region[$v['rg_id']] = $v['rg_name'];
        }
        if(count($userData)<=0){
            $Result['errCode'] = 'L10030';
            $Result['errMsg'] = '抱歉，暂无用户数据！';
            return $Result;
        }
        foreach ($userData as $k => $v){
            $user[$v->id] = [
                'name'=>$v->name,//用户名
                'head_portrait' =>$v->head_portrait_url,//头像地址
            ];
        }
        foreach ($arr as $k => $v){
            $arr[$k]['upload'] = unserialize($v['upload']);
            $arr[$k]['uname'] = base64_decode($user[$v['uid']]['name']);
            // 新增头像字段 --Author:lizhipeng Date:2019.02.15
            $arr[$k]['head_portrait'] = $user[$v['uid']]['head_portrait'];
            // 字符串转换
            $arr[$k]['type_str'] = strToType($v['type']);
            $arr[$k]['gender_str'] = $v['gender']=='1' ? '公' : '母';
            $arr[$k]['charge_str'] = $v['charge']=='1' ? '免费' : '收费';
            $arr[$k]['vaccine_str'] = $v['vaccine']=='1' ? '未注射' : '已注射';
            // 地区转换
            $arr[$k]['region_str'] = $region[$v['region']];
        }

        $Result['data']=$arr;
        return $Result;
    }
}