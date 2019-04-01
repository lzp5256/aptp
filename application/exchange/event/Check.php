<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/4/1
 * Time: 4:16 PM
 */
namespace app\exchange\event;

class Check
{
    protected $data = [];

    public function checkDetailParams($params)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];

        if(empty($params['eid'])){
            $Result['errCode'] = '';
            $Result['errMsg'] = '';
            return $Result;
        }
        $this->data['param']['eid'] = (int)$params['eid'];
        $Result['data']  = $this->data;

        return $Result;

    }
}