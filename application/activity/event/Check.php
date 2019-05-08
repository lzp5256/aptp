<?php
namespace app\activity\event;

class Check
{
    protected $data = [];

    public function checkGetInfoParam($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        if(empty($param['id'])){
            $Res['errCode'] = '10100';
            $Res['errMsg']  = 'failed';
            return $Res;
        }
        $this->data['param']['id'] = (int)$param['id'];

        $Res['data'] = $this->data;
        return $Res;
    }

    public function checkActivityReleaseParam($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        if(empty($param['uid'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = 'failed';
            return $Res;
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['aid'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        if(empty($param['content'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,请输入内容';
            return $Res;
        }
        $this->data['param']['content'] = htmlspecialchars($param['content']);

        if(empty($param['img'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,图片不能为空';
            return $Res;
        }
        $this->data['param']['img'] = (string)$param['img'];

        $Res['data'] = $this->data;
        return $Res;
    }

    public function checkActivityWorksListParam($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        if(empty($param['aid'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        if(empty($param['page'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['page'] = (int)$param['page'];

        $Res['data'] = $this->data;
        return $Res;
    }

    public function checkActivityWorksDetailParam($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        if(empty($param['uid'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['activity_detail_id'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['id'] = (int)$param['activity_detail_id'];

        if(empty($param['action'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['action'] = (string)$param['action'];

        $Res['data'] = $this->data;
        return $Res;
    }

    public function checkActivityWorksCommentListParam($param){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        if(empty($param['uid'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['info_id'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['id'] = (int)$param['info_id'];

        if(empty($param['action'])){
            $Res['errCode'] = '10000';
            $Res['errMsg']  = '抱歉,系统异常,请联系管理员!';
            return $Res;
        }
        $this->data['param']['action'] = (int)$param['action'];

        $Res['data'] = $this->data;
        return $Res;
    }
}