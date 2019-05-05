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
}