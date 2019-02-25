<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/29
 * Time: 11:40 AM
 */
namespace app\demand\event;

use app\apply\model\Apply;
use app\base\controller\Base;
use app\region\model\Region;
use think\Exception;
use app\demand\model\Demand as DemandModel;
use app\user\model\User as UserModel;
use app\region\model\Region as RegionModel;
use app\apply\model\Apply as ApplyModel;

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

    public function handleDetail($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new DemandModel();
        $data = $model->findDemand(['id'=>$params['param']['id']]);

        if(empty($data)){
            $Result['errCode'] = 'L10031';
            $Result['errMsg'] = '抱歉，未查询到相关数据！';
            return $Result;
        }

        // 查询用户
        $userModel =new UserModel();
        $userData = $userModel->findUser(['status'=>'1','id'=>$data['uid']]);
        if(empty($userData)){
            $Result['errCode'] = 'L10030';
            $Result['errMsg'] = '抱歉，暂无用户数据！';
            return $Result;
        }
        // 地区转换
        $regionModel = new Region();
        $regionData = $regionModel->findRegion(['rg_id'=>$data['region']]);

        $data['type'] = strToType($data['type']);
        $data['gender'] = $data['gender'] == '1' ? '公' : '母';
        $data['charge'] = $data['charge'] == '1' ? '免费' : '收费';
        $data['vaccine'] = $data['vaccine'] == '1' ? '未注射' : '已注射';
        $data['upload'] = unserialize($data['upload']);
        $data['uname'] = base64_decode($userData['name']);
        $data['head_portrait'] = $userData['head_portrait_url'];
        $data['region'] = empty($regionData) ? '未知' : $regionData->rg_name;
        $data['updated_at'] = substr($data['updated_at'],0,10);
        $Result['data']=$data;
        return $Result;
    }

    public function handleApply($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        // 先验证是否申请过
        $model = new Apply();
        $findRes = $model->findApply(['uid'=>$params['param']['uid'],'did'=>$params['param']['did']]);
        if($findRes){
            $Result['errCode'] = 'L10039';
            $Result['errMsg'] = '抱歉,您已经申请过了，不可以重复申请！';
            return $Result;
        }
        $data = $this->_getAddApplyData($params);
        $res = $model->addApply($data);
        if(!$res){
            $Result['errCode'] = 'L10038';
            $Result['errMsg'] = '申请失败！';
            return $Result;
        }
        $Result['errMsg'] = '申请成功,稍后会有客服添加您的微信或拨打您电话，请注意接听';
        return $Result;

    }

    protected function _getAddApplyData($params)
    {
        return $data = [
            'uid' => (int)$params['param']['uid'],
            'did' => (int)$params['param']['did'],
            'phone' => (string) $params['param']['phone'],
            'wechat' => (string) $params['param']['wechat'],
            'status' => (int)1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
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