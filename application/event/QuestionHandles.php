<?php
namespace app\event;

use app\base\controller\Base;
use app\model\AskQuestion;
use app\user\event\User;
use app\event\AskUser;

class QuestionHandles extends Base
{
    const STATE_VALID   = '1';
    const STATE_INVALID = '2';
    public function handleQrRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $model = new AskQuestion();
        try{
            $addData = $this->_getAddData();
            if(($add = $model->add($addData)) && $add == 0){
                return $this->setReturnMsg('200003');
            }
            return $res;
        }catch (Exception $e){
            return $this->setReturnMsg('200001');
        }
    }

    public function handleQlRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $model = new AskQuestion();
        $user  = new AskUser();
        try{
            if(!($getQlRes = $model->selectAll(['state' => self::STATE_VALID,'show'=>self::STATE_VALID],$this->data['param_list']['p'],20))){
                return $this->setReturnMsg('200005');
            }
            foreach ($getQlRes as $k => $v){
                $uid[] = $v['uid'];
            }
            $uid = array_unique($uid);
            $userList = $user->setData(['uid'=>$uid])->getAllUserList();
            foreach ($getQlRes as $k => $v){
                $getQlRes[$k]['user_name'] = $userList[$v['uid']]['name'];
                $getQlRes[$k]['browse']    = $v['browse']*10+$v['browse'];
            }
            $res['data'] = $getQlRes;
            return $res;
        }catch (Exception $e){
            return $this->setReturnMsg('200001');
        }
    }

    public function handlesQiRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '获取成功',
            'data'    => [],
        ];
        $model = new AskQuestion();
        $AskUser  = new AskUser();
        try{
            if(!($getQiRes = $model->findOne(['state' => self::STATE_VALID,'show'=>self::STATE_VALID,'qid'=>$this->data['param_list']['qid']]))){
                return $this->setReturnMsg('200005');
            }
            $QiRes = findDataToArray($getQiRes);
            $userList = $AskUser->setData(['uid'=>[$QiRes['uid']]])->getAllUserList();

            $QiRes['user_name'] = $userList[$QiRes['uid']]['name'];
            $res['data'] = $QiRes;
            return $res;
        }catch (Exception $e){
            return $this->setReturnMsg('200001');
        }
    }

    protected function _getAddData(){
        return [
            'state'     => 1,
            'uid'       => (int)$this->data['param_list']['uid'],
            'title'     => (string)$this->data['param_list']['title'],
            'describe'  => (string)$this->data['param_list']['describe'],
            'show'      => 1,
            'created_at'=> date('Y-m-d H:i:s')
        ];
    }
}
