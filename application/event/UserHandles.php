<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Article;
use app\model\UserFollow;
use app\user\event\User;
use app\model\User as UserModel;
use app\helper\helper;
use think\Db;
use think\Exception;

class UserHandles extends Base
{
    public function handleToUserInfoRes()
    {
        $UserModel = new UserModel();
        $UserInfo  = $UserModel->getOne(['status'=>1,'id'=>$this->data['param']['uid']]);
        if(empty($UserInfo)){
            return $this->setReturnMsg('105');
        }
        $info = findDataToArray($UserInfo);
        return $this->setReturnMsg('200',$info);
    }

    // 处理更新用户资料操作
    // Author: lizhipeng
    // Date: 2019.09.04
    // Return: array()
    public function handleToEditRes()
    {
        $helper = new helper();
        try{
            $user_model = new UserModel();
            $update_data = [
                'name' => (string)trim($this->data['param']['name']),
                'sex'  => (int)$this->data['param']['sex'],
                'avatarUrl' => (string)trim($this->data['param']['avatar_url']),
                'wx_user_info' => $this->data['param']['raw_data'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $edit_res = $user_model->toUpdate(['status'=>1,'id'=>(int)$this->data['user_info']['id']],$update_data);
            if(!$edit_res){
                $helper->SendEmail(
                    "用户【".$this->data['user_info']['name']."】【".date('Y-m-d H:i:s')."】更新个人资料失败",
                    "用户ID为\t【".$this->data['param']['uid']."】的用户在." .date('Y-m-d H:i:s')."更新个人资料失败，请尽快处理！\t"
                    );
                return $this->setReturnMsg('103');
            }
            $helper->SendEmail(
                "用户【".$this->data['user_info']['name']."】在【".date('Y-m-d H:i:s')."】更新个人资料",
                "用户ID为\t【".$this->data['param']['uid']."】的用户在." .date('Y-m-d H:i:s')."更新个人资料,请尽快审核！\t"
            );
            return $this->setReturnMsg('200');
        } catch (Exception $e){
            $helper->SendEmail(
                "用户【".$this->data['user_info']['name']."】【".date('Y-m-d H:i:s')."】更新个人资料异常",
                "用户ID为:【".$this->data['user_info']['id']."】异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

    public function handleToFollowRes()
    {
        $helper = new helper();
        $data = [
            'uid' => $this->data['param']['uid'],
            'target' => $this->data['param']['target'],
            'status' => $this->data['param']['type'],
        ];
        try{
            $userFollowModel = new UserFollow();
            $info = $userFollowModel->getOne(['uid'=>$this->data['param']['uid'],'target'=>$this->data['param']['target']]);
            if(empty($info)){
                $data['created_at'] = date('Y-m-d H:i:s');
                $res = $userFollowModel->toAdd($data);
                if(!$res){
                    return $this->setReturnMsg('700003');
                }
            }else{
                $data = [
                    'status' => $this->data['param']['type'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $res = $userFollowModel->toUpdate(['fid'=>$info->fid],$data);
                if(!$res){
                    return $this->setReturnMsg('700003');
                }
            }
            $helper->SendEmail(
                "用户ID【".$this->data['param']['uid']."】在【".date('Y-m-d H:i:s')."】关注了其他用户",
                "用户ID为:【".$this->data['param']['uid']."关注了用户ID为[".$this->data['param']['target']."]】的用户"
            );
            return $this->setReturnMsg('200');
        }catch (Exception $e){
            $helper->SendEmail(
                "用户【".$this->data['param']['uid']."】【".date('Y-m-d H:i:s')."】关注用户操作失败",
                "用户ID为:【".$this->data['param']['uid']."关注用户[".$this->data['param']['target']."]】异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

    public function handleToFollowListRes()
    {
        $helper = new helper();
        try{
            $userFollowModel = new UserFollow();
            if($this->data['param']['type'] == 1){
                $list = $userFollowModel->getAll(['status'=>1,'uid'=>$this->data['param']['uid']],0,20);
                // 获取我的关注用户信息
                $uid_str = 'target';
            }else{
                $list = $userFollowModel->getAll(['status'=>1,'target'=>$this->data['param']['uid']],0,20);
                // 获取关注我的用户信息
                $uid_str = 'uid';
            }
            $list = empty($list) ? [] : selectDataToArray($list);

            // 去重复，原则上是不会有重复的
            $uid_arr = array_unique(array_column($list,$uid_str));

            // 获取用户信息
            $user = new User();
            $user_info_arr = $user->setData(['uid'=>$uid_arr])->getAllUserList();
            foreach ($list as $k => $v) {
                $list[$k]['user']['user_id'] = $user_info_arr[$v[$uid_str]]['id'];
                $list[$k]['user']['user_name'] = $user_info_arr[$v[$uid_str]]['name'];
                $list[$k]['user']['user_src'] = $user_info_arr[$v[$uid_str]]['url'];
                $list[$k]['user']['user_label'] = $user_info_arr[$v[$uid_str]]['label'];
            }
            return $this->setReturnMsg('200',$list);

        }catch (Exception $e){
            $helper->SendEmail(
                "用户【".$this->data['param']['uid']."】【".date('Y-m-d H:i:s')."】获取关注列表操作失败",
                "用户ID为:【".$this->data['param']['uid']."】获取关注列表异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

    // 用户统计相关
    public function handleToTotal()
    {
        $helper = new helper();
        try{
            $data = array();
            $article_model = new Article();
            $follow_model = new UserFollow();
            $article_count = $article_model->getCount([
                'state'=>1,
                'uid'=>$this->data['param']['user_id'],
                'examine'=>1,
            ]);

            $follow_count = $follow_model->getCount([
                'uid' => $this->data['param']['user_id'],
                'status' => 1,
            ]);

            $fans_count = $follow_model->getCount([
                'target' => $this->data['param']['user_id'],
                'status' => 1,
            ]);

            $data = [
                'article_total' => isset($article_count) ? $article_count : 0,
                'follow_total'  => isset($follow_count) ? $follow_count : 0,
                'fans_count'    => isset($fans_count) ? $fans_count : 0,
            ];
            return $this->setReturnMsg('200',$data);
        }catch (Exception $e){
            $helper->SendEmail(
                "用户【".$this->data['param']['user_id']."】【".date('Y-m-d H:i:s')."】获取用户统计操作失败",
                "用户ID为:【".$this->data['param']['user_id']."】获取用户统计操作失败,异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

}
