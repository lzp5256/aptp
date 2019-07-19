<?php
namespace app\event;

use app\base\controller\Base;
use app\model\UserAccount;
use app\model\UserAccountChange;
use think\Db;
use think\Exception;

class IntegralHandles extends Base
{
    protected $user_status = 1;

    public function handleToAddRes()
    {
        $res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];

        Db::startTrans();
        try{
            $userAccountModel = new UserAccount();
            $getUserAccountInfo = $userAccountModel->getOne(['status'=>1,'uid'=>$this->data['param']['uid']]);
            if(empty($getUserAccountInfo)){
                $this->user_status = 2;
                $userAccountRes = $userAccountModel->toAdd($this->_toAddData());
                if(!$userAccountRes){
                    return $this->setReturnMsg('104');
                }
            }

            if($this->user_status == 1){
                $uaid = $getUserAccountInfo->id;
            }else{
                $uaid = $userAccountModel->getLastInsID();
            }
            $userAccountChangeModel = new UserAccountChange();
            $upChange = $userAccountChangeModel->toAdd($this->_toChangeAddData($uaid));
            if(!$upChange){
                Db::rollback();
                return $this->setReturnMsg('104');
            }
            $upAccount = $userAccountModel->setUpdate(['status'=>1,'uid'=>$this->data['param']['uid'],'id'=>$uaid],'Inc','num','50');
            if(!$upAccount){
                Db::rollback();
                return $this->setReturnMsg('103');
            }

            if( $upChange && $upAccount){
                Db::commit();
                return $res;
            }
        }catch (\Exception $e) {
            Db::rollback();
            $res['errCode'] = 10000;
            $res['errMsg'] = $e->getMessage();
            return $res;
        }
    }

    protected function _toAddData(){
        return [
            'uid' => $this->data['param']['uid'],
            'num' => 0,
            'use_num' => 0,
            'status'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    protected function _toChangeAddData($uaid){
        return [
            'uid' => $this->data['param']['uid'],
            'uaid' => $uaid,
            'type' => 1,
            'tid'  => $this->data['task_info']['id'],
            'num'  => $this->data['task_info']['EI'],
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}