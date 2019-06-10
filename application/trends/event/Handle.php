<?php
namespace app\trends\event;

use app\base\controller\Base;
use app\helper\helper;
use app\helper\message;
use app\trends\model\TrendsList;
use app\user\event\User as UserEvent;

class Handle extends Base
{
    const TRENDS_LIST_STATE_VALID   = '1';
    const TRENDS_LIST_STATE_INVALID = '2';

    public function HandleTrendsReleaseRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $save_data = $this->_getHandleTrendsReleaseData();
        $trends_list_model = new TrendsList();

        if(!($add_res = $trends_list_model->getAddTrendsRes($save_data))){
            $Res['errCode'] = '00034';
            $Res['errMsg'] = message::$message['00034'];
            return $Res;
        }

        return $Res;
    }

    public function HandleTrendsInfoRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $trends_list_model = new TrendsList();
        $helper = new helper();
        if(!($trends_info_res = $trends_list_model->getOneTrendsInfo(['id'=>$this->data['param_list']['id'],'state'=>self::TRENDS_LIST_STATE_VALID]))){
            $Res['errCode'] = '00038';
            $Res['errMsg'] = message::$message['00038'];
            return $Res;
        }
        // 获取用户相关信息
        $user = $helper->setData(['uid'=>$trends_info_res->uid])->GetUserStatusById();
        $trends_data = findDataToArray($trends_info_res);
        $trends_data['user'] = [
            'user_name' => $user['name'],
            'user_src'  => $user['head_portrait_url'],
        ];
        $trends_data['src'] = json_decode($trends_data['src'],true);
        $Res['data'] = $trends_data;
        return $Res;
    }

    public function HandleTrendsListRes(){
        $Res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $trends_list_model = new TrendsList();
        $helper = new helper();
        if(!($trends_list_res = $trends_list_model->getTrendsPageList(['state'=>self::TRENDS_LIST_STATE_VALID],$this->data['param_list']['page'],10))){
            $Res['errCode'] = '00040';
            $Res['errMsg'] = message::$message['00040'];
            return $Res;
        }
        $trends_list_res = selectDataToArray($trends_list_res);
        $uid_arr = [];
        foreach ($trends_list_res as $k => $v){
            $uid_arr[] = $v['uid'];
        }

        $allUid = array_unique($uid_arr);
        $event = new UserEvent();
        // 获取相关用户信息
        $userData = $event->setData(['uid'=>$allUid])->getAllUserList();

        foreach ($trends_list_res as $k => $v){
            $trends_list_res[$k]['name'] = $userData[$v['uid']]['name'];
            $trends_list_res[$k]['user_url'] = $userData[$v['uid']]['url'];
            $trends_list_res[$k]['src'] = json_decode($v['src'],true);
        }

        $Res['data'] = $trends_list_res;
        return $Res;
    }

    protected function _getHandleTrendsReleaseData(){
        return [
            'uid'     => (int)$this->data['param_list']['uid'],
            'title'   => (string)$this->data['param_list']['title'],
            'content' => (string)$this->data['param_list']['content'],
            'src'     => (string)$this->data['param_list']['src'],
            'likes'   => 0,
            'state'   => 1,
            'cretaed_at' => date('Y-m-d H:i:s'),
        ];
    }
}