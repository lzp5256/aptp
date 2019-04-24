<?php
namespace app\dynamic\event;

use app\base\controller\Base;
use app\dynamic\model\Dynamic;
use app\dynamic\model\DynamicComment;
use app\user\event\User as UserEvent;

class Handle extends Base
{
    protected $log_level = 'error';

    public function handleDynamicInfo()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => '验证成功',
            'data'    => [],
        ];
        $list = [];
        $dynamicModel = new Dynamic();
        $findDynamicInfo = $dynamicModel->findArticle(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findDynamicInfo)){
            $Result['errCode'] = 'L10080';
            $Result['errMsg'] = '抱歉,未查询到此动态详情';
            writeLog((getWriteLogInfo('获取动态详情,查询详情失败!',json_encode($this->data),$dynamicModel->getLastSql())),$this->log_level);
            return $Result;
        }
        $list[0]['dynamic_list'] = $findDynamicInfo->toArray();
        $event = new UserEvent();
        $userData = $event->setData(['uid'=>[$findDynamicInfo->uid]])->getAllUserList();
        if(empty($userData)){
            writeLog((getWriteLogInfo('获取动态详情,用户查询失败!',json_encode(['uid'=>$findDynamicInfo->uid]),'')),$this->log_level);
        }
        $list[0]['dynamic_list']['user_name'] = $userData[$findDynamicInfo->uid]['name'];
        $list[0]['dynamic_list']['user_url'] = $userData[$findDynamicInfo->uid]['url'];

        // 获取评论列表 ，一次100条
        $comemntModel =  new DynamicComment();
        $selectCommentList = $comemntModel->getDynamicCommentList(['did'=>$this->data['param']['did'],'status'=>1],0,100);
        if(empty($selectCommentList)){
            $list[0]['dynamic_comment_list'] = [];
            $Result['data'] = $list;
            return $Result;
        }

        // 获取用户信息
        $getAllUid = [];
        foreach ($selectCommentList as $k => $v){
            $getAllUid[] = $v['uid'];
        }

        $allUid = array_unique($getAllUid);
        $event = new UserEvent();
        $userData = $event->setData(['uid'=>$allUid])->getAllUserList();
        foreach ($selectCommentList as $k => $v){
            $selectCommentList[$k]['name'] = $userData[$v['uid']]['name'];
            $selectCommentList[$k]['user_url'] = $userData[$v['uid']]['url'];
        }

        $list[0]['dynamic_comment_list'] = $selectCommentList;
        $Result['data'] = $list;
        return $Result;
    }
}
