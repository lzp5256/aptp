<?php
namespace app\event\Message;

use app\base\controller\Base;
use app\helper\helper;
use app\model\SysMessage;
use app\model\UserComment;
use app\model\UserLikes;
use think\Exception;

class MessageHandles extends Base
{
    public function handleToMessageList()
    {
        $helper = new  helper();
        try{
            $message_model = new SysMessage();
            $message_list = $message_model->getAllList(['status'=>1]);
            if(empty($message_list)){
                return $this->setReturnMsg('200');
            }
            $total = 0;
            $list  = [];
            foreach ($message_list as $k => $value) {
                // type = 1 | 统计未读系统消息数量
                if ($value['type']  == 1 && $value['read'] != 1){
                    $total += 1;
                }
                // type = 2 | 评论消息
                if ($value['type']  == 2 && $value['read'] != 1){
                    $user_comments_model = new UserComment();
                    $comments_list = $user_comments_model->getOne(['state'=>'1','id'=>$value['type_id'],'uid'=>(int)$this->data['params']['uid']]);
                    if(!empty($comments_list)){
                        $list['comments'][] = findDataToArray($comments_list);
                    }
                }
                // type = 3 | 点赞消息
                if ($value['type']  == 3 && $value['read'] != 1){
                    $user_likes_model = new UserLikes();
                    $likes_list = $user_likes_model->getOne(['state'=>'1','id'=>$value['type_id'],'uid'=>(int)$this->data['params']['uid']]);
                    if(!empty($likes_list)){
                        $list['likes'][] = findDataToArray($likes_list);
                    }
                }
                $list['total'] = $total;
            }
            return $this->setReturnMsg('200',$list);
        }catch (Exception $e){
            $helper->SendEmail(
                "获取消息列表异常【异常时间:".date('Y-m-d H:i:s')."】",
                "获取消息列表异常,异常信息:".$e->getMessage()
            );
            $this->setReturnMsg('502');
        }
    }
}