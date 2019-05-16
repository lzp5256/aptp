<?php
namespace  app\adopt\event;

use app\adopt\model\AdoptList;
use app\base\controller\Base;
use app\helper\helper;
use app\helper\message;
use app\region\model\Region;
use app\user\event\User as UserEvent;

class Handle extends Base
{
    protected $log_level = 'error';  //定义日志级别

    // 处理新增领养信息操作
    public function handleAddAdopt(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => '添加成功',
            'data'    => [],
        ];
        $addData = $this->_addAdoptData();
        $model = new AdoptList();
        if(!($res = $model->getAddAdoptRes($addData))){
            $return_res['errCode'] = '00025';
            $return_res['errMsg'] = message::$message['00025'];
        }
        $this->_sendEmailMessage();
        return $return_res;
    }

    // 处理新增领养信息操作
    public function handleAdoptDetailRes(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => '添加成功',
            'data'    => [],
        ];
        $model = new AdoptList();
        $getAdoptInfo = $model->getOneAdoptInfo(['id'=>(int)$this->data['check_param_list']['id'],'state'=>1]);
        if(empty($getAdoptInfo)){
            $return_res['errCode'] = '00027';
            $return_res['errMsg'] = message::$message['00027'];
            return $return_res;
        }
        $data = findDataToArray($getAdoptInfo);

        $event = new UserEvent();
        $userData = $event->setData(['uid'=>[$data['uid']]])->getAllUserList();
        if(empty($userData)){
            writeLog((getWriteLogInfo('获取动态详情,用户查询失败!',json_encode(['uid'=>$data['uid']]),'')),$this->log_level);
        }
        $data['user_name'] = $userData[$data['uid']]['name'];
        $data['user_url']  = $userData[$data['uid']]['url'];

        // 数据转换
        $helper = new helper();
        $flag_list = $helper->GetFlagList();

        $data['imgList'] = json_decode($getAdoptInfo['imgList'],true);
        $data['age'] = $flag_list['age'][$data['age']];
        $data['sex'] = $flag_list['sex'][$data['sex']];
        $data['type'] = $flag_list['type'][$data['type']];
        $data['vaccine'] = $flag_list['vaccine'][$data['vaccine']];
        $data['sterilization'] = $flag_list['sterilization'][$data['sterilization']];
        $data['insectRepellent'] = $flag_list['insectRepellent'][$data['insectRepellent']];
        $condition = explode(',',$data['condition']);
        foreach ($condition as $k => $v){
            $data['condition_arr'][$k] = $flag_list['condition'][$v];
        }
        $data['source_name'] = $flag_list['source'][$data['source']];
        $model = new Region();
        $info  = $model->findRegion(['status'=>1,'rg_id'=>$data['city'],'delete_flag'=>0,'rg_level'=>'2']);
        if(empty($info)){
            $data['city_name'] ='-';
        }
        $data['city_name'] = $info->rg_name;
        $return_res['data'] = $data;
        return $return_res;

    }

    protected function _addAdoptData(){
        return [
            'uid'       => $this->data['check_list']['uid'],
            'imgList'   => $this->data['check_list']['imgList'],
            'name'      => $this->data['check_list']['name'],
            'age'       => $this->data['check_list']['age'],
            'sex'       => $this->data['check_list']['sex'],
            'type'      => $this->data['check_list']['type'],
            'charge'    => $this->data['check_list']['charge'],
            'source'    => $this->data['check_list']['source'],
            'shape'     => $this->data['check_list']['shape'],
            'hair'      => $this->data['check_list']['hair'],
            'vaccine'   => $this->data['check_list']['vaccine'],
            'sterilization'     => $this->data['check_list']['sterilization'],
            'insectRepellent'   => $this->data['check_list']['insectRepellent'],
            'condition' => $this->data['check_list']['condition'],
            'describe'  => $this->data['check_list']['describe'],
            'user_wechat'    => $this->data['check_list']['wechat'],
            'user_phone'     => $this->data['check_list']['phone'],
            'province'  => $this->data['check_list']['province'],
            'city'      => $this->data['check_list']['city'],
            'area'      => $this->data['check_list']['area'],
            'address'   => $this->data['check_list']['address'],
            'wShow'     => $this->data['check_list']['wShow'],
            'pShow'     => $this->data['check_list']['pShow'],
            'message'   => $this->data['check_list']['message'],
            'state'     => 1,
            'createdAt' => date('Y-m-d H:i:s'),
            'origin'    => 1,
            'adoptState'=> 1,
        ];
    }


    protected function _sendEmailMessage(){
        $helper = new helper();
        // 拼接内容
        $title = date('Y-m-d H:i:s')."新增领养信息";
        $content = "用户ID为\t【".$this->data['check_list']['uid']."】的用户在." .date('Y-m-d H:i:s')."添加一条新的领养信息\t";
        $res = $helper->SendEmail($title,$content);
        if($res != '1'){
            writeLog(getWriteLogInfo('邮件异常','title:'.$title,'content:'.$content,$this->log_level));
        }

    }
}
