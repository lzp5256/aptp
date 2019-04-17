<?php
namespace app\release\event;

class Check
{
    protected $data = [];
    /**
     * @desc 验证参数
     * @param int    index   问题类型索引
     * @param int    uid     用户id
     * @param string title   问题标题
     * @param string upload  图片地址
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function checkQaParam($param){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];
        if(!isset($param['pet_type']) || $param['pet_type'] =='9999'){
            $Result['errCode'] = 'L10060';
            $Result['errMsg'] = '错误码[L10060]';
            return $Result;
        }
        $this->data['param']['pet_type'] = $param['pet_type'];

        if(!isset($param['qa_type']) || $param['qa_type'] =='9999'){
            $Result['errCode'] = 'L10061';
            $Result['errMsg'] = '错误码[L10061]';
            return $Result;
        }
        $this->data['param']['QA_type'] = $param['qa_type'];

        if(empty($param['uid'])){
            $Result['errCode'] = 'L10062';
            $Result['errMsg'] = '错误码[L10062]';
            return $Result;
        }
        $this->data['param']['uid'] = $param['uid'];

        if(empty($param['title'])){
            $Result['errCode'] = 'L10063';
            $Result['errMsg'] = '错误码[L10063]';
            return $Result;
        }
        $this->data['param']['title'] = $param['title'];

        if(empty($param['upload'])){
            $Result['errCode'] = 'L10064';
            $Result['errMsg'] = '错误码[L10064]';
            return $Result;
        }
        $this->data['param']['upload'] = $param['upload'];

        $Result['data'] = $this->data;
        return $Result;
    }
}
