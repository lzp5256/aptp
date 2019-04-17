<?php
namespace app\release\event;

use app\base\controller\Base;
use app\user\model\User;
use app\qa\model\Qa;

class Handle extends Base
{
    /**
     * @desc 发布问答处理
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseQaRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10065';
            $Result['errMsg'] = '错误码[L10065]';
            return $Result;
        }
        $qaModel = new Qa();
        $saveRes = $qaModel->addQa($this->_getAddQaData());
        if(!$saveRes){
            $Result['errCode'] = 'L10066';
            $Result['errMsg'] = '错误码[L10066]';
            return $Result;
        }
        return $Result;
    }

    protected function _getAddQaData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'title'     => $this->data['param']['title'],
            'pic_url'   => isset($this->data['param']['upload']) ? $this->data['param']['upload'] : '' ,
            'pet_type'  => $this->data['param']['pet_type'],
            'QA_type'   => $this->data['param']['QA_type'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }
}
