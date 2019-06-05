<?php
namespace app\trends\event;

use app\base\controller\Base;
use app\helper\message;

class Check extends Base
{
    protected $data = [];

    public function CheckTrendsReleaseParamRes($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        if(!isset($param['uid']) || $param['uid'] <= 0 || empty($param['uid'])){
            $Res['errCode'] = '00001';
            $Res['errMsg'] = message::$message['00001'];
            return $Res;
        }
        $this->data['param_list']['uid'] = (int)$param['uid'];

        if(!isset($param['src']) || empty($param['src']) || $param['src'] == '[]' ){
            $return_res['errCode'] = '00035';
            $return_res['errMsg'] = message::$message['00035'];
            return $return_res;
        }

        if(count(json_decode($param['src'],true)) < 1){
            $return_res['errCode'] = '00036';
            $return_res['errMsg'] = message::$message['00036'];
            return $return_res;
        }
        $this->data['param_list']['src'] = (string)$param['src'];

        if(!isset($param['title']) || empty($param['title']) ){
            $Res['errCode'] = '00029';
            $Res['errMsg'] = message::$message['00029'];
            return $Res;
        }
        if(mb_strlen($param['title']) < 5 ){
            $Res['errCode'] = '00031';
            $Res['errMsg'] = message::$message['00031'];
            return $Res;
        }
        $this->data['param_list']['title'] = (string)trim($param['title']);

        if(!isset($param['content']) || empty($param['content'])){
            $Res['errCode'] = '00030';
            $Res['errMsg'] = message::$message['00030'];
            return $Res;
        }
        if(mb_strlen($param['content']) < 20 ){
            $Res['errCode'] = '00032';
            $Res['errMsg'] = message::$message['00032'];
            return $Res;
        }
        if(mb_strlen($param['content']) > 600 ){
            $Res['errCode'] = '00033';
            $Res['errMsg'] = message::$message['00033'];
            return $Res;
        }
        $this->data['param_list']['content'] = (string)trim($param['content']);

        $Res['data'] = $this->data;
        return $Res;

    }
}
