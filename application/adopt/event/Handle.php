<?php
namespace  app\adopt\event;

use app\adopt\model\AdoptList;
use app\base\controller\Base;
use app\helper\helper;
use app\helper\message;

class Handle extends Base
{
    protected $log_level = 'error';  //定义日志级别

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
