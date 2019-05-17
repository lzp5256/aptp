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
use app\helper\helper;
use app\region\model\Region;
use app\task\event\Task;
use app\user\event\UserCbAccountChange;
use think\Exception;
use app\demand\model\Demand as DemandModel;
use app\user\model\User as UserModel;
use app\region\model\Region as RegionModel;
use app\apply\model\Apply as ApplyModel;
use app\user\event\UserCbAccount as UserCbAccountEvent;
use app\task\event\Task as TaskEvent;
use app\user\event\UserCbAccountChange as UserCbAccountChageEvent;

class Demand
{
    public function handle($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功!',
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

            if( $res && isset($data['param']['tid']) && ($data['param']['tid'] > 0) ){
                $userCbAccountChangeEvent = new UserCbAccountChageEvent();
                $checkRes = $userCbAccountChangeEvent->setData($data)->checkCompleteState();
                if(($checkRes) && $checkRes['errCode'] =='200'){
                    $data['param']['type'] = '1'; //类型为1(添加)
                    $userCbAccountEvent = new UserCbAccountEvent();
                    $taskEvent = new TaskEvent();
                    $taskRes = $taskEvent->setData($data)->_checkTask();
                    if($taskRes['errCode'] == '200')$data['task_list'] = $taskRes['data']['task_list'];
                    $updateUserCbRes = $userCbAccountEvent->setData($data)->updateUserCb();
                    if($updateUserCbRes['errCode'] == '200')$data['user_cb_account'] = $updateUserCbRes['data']['user_cb_account'];
                    $userCbAccountChangeEvent->setData($data)->updateUserCbAccountChange();
                }else{
                    //TODO 添加日志
                }


            }

        }catch (Exception $e){
            $Result['errCode'] = 'L10028';
            $Result['errMsg'] = $e->getMessage();
            return $Result;
        }
        return $Result;
    }

    /**
     * @desc 处理详情信息
     * @param $params
     * @return array
     */
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

    /**
     * @desc 处理申请方法
     * @param $params
     * @return array
     */
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
        if(!empty($findRes)){
            $Result['errCode'] = 'L10043';
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
        $helper = new helper();
        // 拼接内容
        $title = "新增申请领养信息";
        $content = "用户ID为\t【".$params['param']['uid']."】的用户在." .date('Y-m-d H:i:s')."申请了ID为".$params['param']['did']."的一条领养信息\t";
        $res = $helper->SendEmail($title,$content);
        if($res != '1'){
            writeLog(getWriteLogInfo('邮件异常','title:'.$title,'content:'.$content,$this->log_level));
        }

        $Result['errMsg'] = '申请成功,稍后会有客服添加您的微信与您联系，请注意查看';
        return $Result;

    }

    /**
     * @desc 处理我的送养信息
     * @date 2019.03.10
     * @param $params
     * @return array
     */
    public function handleMyRelease($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new DemandModel();
        $getUserReleasList = $model->selectDemand(['uid'=>(string)$params['param']['uid'],'status'=>'1'],0,10);
        if(empty($getUserReleasList)){
            $Result['errCode'] = 'L10046';
            $Result['errMsg'] = '抱歉,未获取到数据!';
            return $Result;
        }
        foreach ($getUserReleasList as $k => $v){
            $getUserReleasList[$k]['type_str'] = strToType($v['type']);
            $getUserReleasList[$k]['gender_str'] = $v['gender']=='1' ? '公' : '母';
            $getUserReleasList[$k]['charge_str'] = $v['charge']=='1' ? '免费' : '收费';
            $getUserReleasList[$k]['vaccine_str'] = $v['vaccine']=='1' ? '未注射' : '已注射';
            $getUserReleasList[$k]['upload'] = unserialize($v['upload']);
        }
        $Result['data'] = $getUserReleasList;
        return $Result;
    }

    /**
     * @desc 获取添加请求参数
     * @param $params
     * @return array
     */
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
            if($k != 'tid'){
                $arr[$k] = $v;
            }
        }
        $arr['status'] = '1';
        $arr['created_at'] = date('Y-m-d H:i:s');
        return $arr;
    }

}