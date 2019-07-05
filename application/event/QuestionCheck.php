<?php
namespace app\event;

use app\base\controller\Base;

class QuestionCheck extends Base
{
    public function checkQrParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['uid']) || !isset($param['uid']) || $param['uid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['uid'] = (int)$param['uid'];

        if (empty($param['title'])){
            return $this->setReturnMsg('200002');
        }
        $this->data['param_list']['title'] = (string)$param['title'];

        if (isset($param['describe']) && !empty($param['describe']) ){
            $this->data['param_list']['describe'] = trim($param['describe']);
        }
        $this->data['param_list']['describe'] = '';

        $res['data'] = $this->data;
        return $res;

    }

    public function checkQlParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['p']) || !isset($param['p']) || $param['p'] <= 0){
            return $this->setReturnMsg('200004');
        }
        $this->data['param_list']['p'] = (int)$param['p'];

        $res['data'] = $this->data;
        return $res;
    }

    public function checkQbParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['qid']) || !isset($param['qid']) || $param['qid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['qid'] = (int)$param['qid'];

        $res['data'] = $this->data;
        return $res;
    }

    public function checkQiParams($param){
        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['qid']) || !isset($param['qid']) || $param['qid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['qid'] = (int)$param['qid'];

        if (empty($param['uid']) || !isset($param['uid']) || $param['uid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['uid'] = (int)$param['uid'];

        $res['data'] = $this->data;
        return $res;
    }

    public function checkQcParams($param){

        $res = [
            'errCode' => '200',
            'errMsg'  => '校验成功',
            'data'    => [],
        ];
        if (empty($param['qid']) || !isset($param['qid']) || $param['qid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['qid'] = (int)$param['qid'];

        if (empty($param['uid']) || !isset($param['uid']) || $param['uid'] <= 0){
            return $this->setReturnMsg('200001');
        }
        $this->data['param_list']['uid'] = (int)$param['uid'];

        if (empty($param['content']) || !isset($param['content'])){
            return $this->setReturnMsg('200008');
        }

        if (mb_strlen($param['content']) <= 5){
            return $this->setReturnMsg('200009');
        }
        $this->data['param_list']['content'] = (string)$param['content'];
        $this->data['time'] = date('Y-m-d H:i:s');
        $res['data'] = $this->data;
        return $res;
    }
}