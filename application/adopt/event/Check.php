<?php
namespace app\adopt\event;

use app\base\controller\Base;
use app\helper\helper;
use app\helper\message;

class Check extends Base
{
    /**
     * @desc 索引值为0,1,2......
     * @param  int    $uid 用户id
     * @param  array  $imgList 上传的图片数组
     * @param  string $name 昵称
     * @param  string $age 年龄索引
     * @param  int    $sex 性别索引
     * @param  int    $type 类别索引
     * @param  int    $charge 是否收费索引
     * @param  int    $source 来源索引
     * @param  int    $shape 体型索引
     * @param  int    $hair 毛发索引
     * @param  int    $vaccine 疫苗索引
     * @param  int    $sterilization 绝育索引
     * @param  int    $insectRepellent 驱虫索引
     * @param  string $condition 领养条件
     * @param  string $describe 描述
     * @param  string $wechat 微信
     * @param  string $phone  手机号码
     * @param  string $city 城市
     * @param  string $address 详细地址
     * @param  int    $wShow 展示微信索引
     * @param  int    $pShow 展示手机索引
     * @param  int    $message 展示消息通知索引
     * @return array
     */
    public function CheckAddParam(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        // 获取所有标签
        $helper = new helper();
        $flag_list = $helper->GetFlagList();

        // 获取$this->data中的param参数列表,验证通过后保存到$this->data['check_list']中
        $param = $this->data['param'];
        if(!isset($param['uid']) || empty($param['uid']) ){
            $return_res['errCode'] = '00001';
            $return_res['errMsg'] = message::$message['00001'];
            return $return_res;
        }
        $this->data['check_list']['uid'] = (int)$param['uid'];

        if(!isset($param['imgList']) || empty($param['imgList']) || $param['imgList'] == '[]' ){
            $return_res['errCode'] = '00002';
            $return_res['errMsg'] = message::$message['00002'];
            return $return_res;
        }

        if(count(json_decode($param['imgList'],true)) < 1){
            $return_res['errCode'] = '00028';
            $return_res['errMsg'] = message::$message['00028'];
            return $return_res;
        }

        $this->data['check_list']['imgList'] = (string)$param['imgList'];

        if(!isset($param['name']) || empty($param['name']) ){
            $return_res['errCode'] = '00003';
            $return_res['errMsg'] = message::$message['00003'];
            return $return_res;
        }
        $this->data['check_list']['name'] = (string)str_replace(' ','',$param['name']);

        if(!isset($param['age']) || $param['age'] < 0 || $param['age'] == '' || !in_array($param['age'],array_keys($flag_list['age']))){
            $return_res['errCode'] = '00004';
            $return_res['errMsg'] = message::$message['00004'];
            return $return_res;
        }
        $this->data['check_list']['age'] = (int)$param['age'];

        if(!isset($param['sex']) || $param['sex'] < 0 || $param['sex'] == '' || !in_array((int)$param['sex'],array_keys($flag_list['sex']),true)){
            $return_res['errCode'] = '00005';
            $return_res['errMsg'] = message::$message['00005'];
            return $return_res;
        }
        $this->data['check_list']['sex'] = (int)$param['sex'];

        if(!isset($param['type']) || $param['type'] < 0 || $param['type'] == '' || !in_array((int)$param['type'],array_keys($flag_list['type']))){
            $return_res['errCode'] = '00006';
            $return_res['errMsg'] = message::$message['00006'];
            return $return_res;
        }
        $this->data['check_list']['type'] = (int)$param['type'];

        if(!isset($param['charge']) || $param['charge'] < 0 || $param['charge'] == '' || !in_array((int)$param['charge'],array_keys($flag_list['charge']))){
            $return_res['errCode'] = '00007';
            $return_res['errMsg'] = message::$message['00007'];
            return $return_res;
        }
        $this->data['check_list']['charge'] = (int)$param['charge'];

        if(!isset($param['source']) || $param['source'] < 0 || $param['source'] == '' || !in_array((int)$param['source'],array_keys($flag_list['source']))){
            $return_res['errCode'] = '00008';
            $return_res['errMsg'] = message::$message['00008'];
            return $return_res;
        }
        $this->data['check_list']['source'] = (int)$param['source'];

        if(!isset($param['hair']) || $param['hair'] < 0 || $param['hair'] == '' || !in_array((int)$param['hair'],array_keys($flag_list['hair']))){
            $return_res['errCode'] = '00009';
            $return_res['errMsg'] = message::$message['00009'];
            return $return_res;
        }
        $this->data['check_list']['hair'] = (int)$param['hair'];

        if(!isset($param['shape']) || $param['shape'] < 0 || $param['shape'] == '' || !in_array((int)$param['shape'],array_keys($flag_list['shape']))){
            $return_res['errCode'] = '00010';
            $return_res['errMsg'] = message::$message['00010'];
            return $return_res;
        }
        $this->data['check_list']['shape'] = (int)$param['shape'];

        if(!isset($param['vaccine']) || $param['vaccine'] < 0 || $param['vaccine'] == '' || !in_array((int)$param['vaccine'],array_keys($flag_list['vaccine']))){
            $return_res['errCode'] = '00011';
            $return_res['errMsg'] = message::$message['00011'];
            return $return_res;
        }
        $this->data['check_list']['vaccine'] = (int)$param['vaccine'];

        if(!isset($param['sterilization']) || $param['sterilization'] < 0 || $param['sterilization'] == '' || !in_array((int)$param['sterilization'],array_keys($flag_list['sterilization']))){
            $return_res['errCode'] = '00012';
            $return_res['errMsg'] = message::$message['00012'];
            return $return_res;
        }
        $this->data['check_list']['sterilization'] = (int)$param['sterilization'];

        if(!isset($param['insectRepellent']) || $param['insectRepellent'] < 0 || $param['insectRepellent'] == '' || !in_array((int)$param['insectRepellent'],array_keys($flag_list['insectRepellent']))){
            $return_res['errCode'] = '00013';
            $return_res['errMsg'] = message::$message['00013'];
            return $return_res;
        }
        $this->data['check_list']['insectRepellent'] = (int)$param['insectRepellent'];

        if(!isset($param['condition']) || empty($param['condition'])){
            $return_res['errCode'] = '00026';
            $return_res['errMsg'] = message::$message['00026'];
            return $return_res;
        }
        $this->data['check_list']['condition'] = (string)$param['condition'];

        if(!isset($param['describe']) || empty($param['describe'])){
            $return_res['errCode'] = '00014';
            $return_res['errMsg'] = message::$message['00014'];
            return $return_res;
        }
        if(strlen($param['describe']) > 600){
            $return_res['errCode'] = '00015';
            $return_res['errMsg'] = message::$message['00015'];
            return $return_res;
        }
        $this->data['check_list']['describe'] = (string)$param['describe'];

        if(!isset($param['wechat']) || empty($param['wechat'])){
            $return_res['errCode'] = '00016';
            $return_res['errMsg'] = message::$message['00016'];
            return $return_res;
        }
        $this->data['check_list']['wechat'] = (string)str_replace(' ','',$param['wechat']);

        if(!isset($param['phone']) || empty($param['phone'])){
            $return_res['errCode'] = '00017';
            $return_res['errMsg'] = message::$message['00017'];
            return $return_res;
        }
        if(strlen($param['phone']) != 11){
            $Result['errCode'] = '00018';
            $Result['errMsg'] = message::$message['00018'];
            return $Result;
        }
        if(!isMobile($param['phone'])){
            $Result['errCode'] = '00019';
            $Result['errMsg'] = message::$message['00019'];
            return $Result;
        }
        $this->data['check_list']['phone'] = (string)$param['phone'];

        if(!isset($param['city']) || empty($param['city'])){
            $return_res['errCode'] = '00020';
            $return_res['errMsg'] = message::$message['00020'];
            return $return_res;
        }
        $cityArr = explode(':',$param['city']);
        $this->data['check_list']['province'] = isset($cityArr[0]) ? $cityArr[0] : 0;
        $this->data['check_list']['city'] = isset($cityArr[1]) ? $cityArr[1] : 0;
        $this->data['check_list']['area'] = isset($cityArr[2]) ? $cityArr[2] : 0;

        if(!isset($param['address']) || empty($param['address'])){
            $return_res['errCode'] = '00021';
            $return_res['errMsg'] = message::$message['00021'];
            return $return_res;
        }
        $this->data['check_list']['address'] = (string)$param['address'];

        if(!isset($param['wShow']) || $param['wShow'] < 0 || $param['wShow'] == '' || !in_array((int)$param['wShow'],array_keys($flag_list['weixin']))){
            $return_res['errCode'] = '00022';
            $return_res['errMsg'] = message::$message['00022'];
            return $return_res;
        }
        $this->data['check_list']['wShow'] = (int)$param['wShow'];

        if(!isset($param['pShow']) || $param['pShow'] < 0 || $param['pShow'] == '' || !in_array((int)$param['wShow'],array_keys($flag_list['phone']))){
            $return_res['errCode'] = '00023';
            $return_res['errMsg'] = message::$message['00023'];
            return $return_res;
        }
        $this->data['check_list']['pShow'] = (int)$param['pShow'];

        if(!isset($param['message']) || $param['message'] < 0 || $param['message'] == '' || !in_array((int)$param['message'],array_keys($flag_list['message']))){
            $return_res['errCode'] = '00024';
            $return_res['errMsg'] = message::$message['00024'];
            return $return_res;
        }
        $this->data['check_list']['message'] = (int)$param['message'];

        $return_res['data'] = $this->data;
        return $return_res;
    }


    public function CheckAdoptDetailParam(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        // 获取$this->data中的param参数列表,验证通过后保存到$this->data['check_list']中
        $param = $this->data['param'];
        if(!isset($param['id']) || empty($param['id']) || $param['id']<=0 ){
            $return_res['errCode'] = '00026';
            $return_res['errMsg'] = message::$message['00026'];
            return $return_res;
        }
        $this->data['check_param_list']['id'] = (int)$param['id'];
        $return_res['data'] = $this->data;
        return $return_res;
    }
}
