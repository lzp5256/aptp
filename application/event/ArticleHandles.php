<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Article;
use app\model\Circle;
use app\model\SysImages;
use app\model\SysMessage;
use app\model\UserCircle;
use app\model\UserComment;
use app\model\UserFollow;
use app\model\UserLikes;
use app\user\event\User;
use app\helper\helper;
use think\Db;
use think\Exception;

class ArticleHandles extends Base
{
    protected $sys_fun_type_ad = '1';

    // 获取我发布的列表
    public function handleToTrendsListRes()
    {
        $helper = new helper();
        try{
            $article_model = new Article();
            $article_list = $article_model->getAll(['uid'=>$this->data['params']['uid'],'state'=>1,'examine'=>1],$this->data['params']['page'],10);
            $article_list = empty($article_list)?[]:selectDataToArray($article_list);

            if(empty($article_list)){
                return $this->setReturnMsg('200',[]);
            }

            $article_list   = empty($article_list) ? array() : selectDataToArray($article_list);

            $UserEvent = new User();
            $all_user_id = array_unique(array_column($article_list,'uid'));
            $UserInfo = $UserEvent->setData(['uid'=>$all_user_id])->getAllUserList();

            foreach ($article_list as $k => $v){

                $article_list[$k]['user_name'] = $UserInfo[$v['uid']]['name'];
                $article_list[$k]['user_src'] = $UserInfo[$v['uid']]['url'];
                $article_list[$k]['time'] = $helper->time_tran($v['time']);

                // 获取动态图片
                $sys_images_list = $helper->getSysImagesByUid([$v['id']],'1');
                $article_list[$k]['pic_list'] = [];

                if(!empty($sys_images_list)){
                    $article_list[$k]['pic_list'] = json_decode($sys_images_list['src'],true);
                }

            }
            return $this->setReturnMsg('200',$article_list);
        }catch (Exception $e){
            return $this->setReturnMsg('502');
        }
    }

    public function handleToInfoRes()
    {
        $helper = new helper();
        try{
            $aid = $this->data['param']['aid'];

            $ArticleModel = new Article();
            $info = $ArticleModel->getOne(['state'=>1,'id'=>(int)$aid]);
            $info = empty($info) ? array() : findDataToArray($info);
            $UserEvent = new User();
            $UserInfo = $UserEvent->setData(['uid'=>[$info['uid']]])->getAllUserList();

            $info['user_name'] = $UserInfo[$info['uid']]['name'];
            $info['user_src']  = $UserInfo[$info['uid']]['url'];
            $info['time'] = $helper->time_tran($info['time']);
            $info['label'] = $UserInfo[$info['uid']]['label'];

            // 获取动态图片
            $sys_images_list = $helper->getSysImagesByUid([$aid],$this->sys_fun_type_ad);
            if(!empty($sys_images_list)){
                $info['src'] = json_decode($sys_images_list['src'],true);
            }

            // 所属圈子
            if(!empty($info)){
                $circle_model = new Circle();
                $circle_info = $circle_model->getOne(['cid'=>$info['circle_id'],'status'=>1,'audit_status'=>1]);
                if(!empty($circle_info)){
                    $info['circle_info'] = findDataToArray($circle_info);
                    $sys_images_info = $helper->getSysImagesByid([$info['circle_info']['sid']],3);
                    $info['circle_info']['sys_url'] = json_decode($sys_images_info['src'],true)[0];
                }else{
                    $info['circle_info'] = [];
                }

                // 用户是否关注当前宠圈
                $info['circle_info']['is_join'] = 2;
                $user_circle_model = new UserCircle();
                $user_circle_info = $user_circle_model->getOne([
                    'status'=>1,
                    'uid'=>$this->data['param']['user_id'],
                    'target'=>$info['circle_id']
                ]);
                if(!empty($user_circle_info)){
                    $info['circle_info']['is_join'] = 1;
                }
            }

            // 是否关注作者
            $user_follow_model = new UserFollow();
            $user_follow_info = $user_follow_model->getOne([
                'status'=>1,
                'uid' => $this->data['param']['user_id'],
                'target' => $info['uid']
            ]);

            if($user_follow_info){
                $info['is_follow'] = 1;
            }

            return $this->setReturnMsg('200',$info);

        }catch (Exception $e){
            var_dump($e->getMessage());
            return $this->setReturnMsg('502');
        }
    }

    public function handleToListRes()
    {
        $helper = new helper();

        $page   = $this->data['param']['page'];

        $ArticleModel = new Article();
        $list   = $ArticleModel->getAll(['state'=>1,'examine'=>1],$page,10,'*','browse desc');
        $list   = empty($list) ? array() : selectDataToArray($list);


        $UserEvent = new User();
        $all_user_id = array_unique(array_column($list,'uid'));
        $UserInfo = $UserEvent->setData(['uid'=>$all_user_id])->getAllUserList();
        foreach ($list as $k => $v){
            $list[$k]['user_name'] = $UserInfo[$v['uid']]['name'];
            $list[$k]['user_src'] = $UserInfo[$v['uid']]['url'];
            $list[$k]['time'] = $helper->time_tran($v['time']);
            if($v['type'] == 2){
                // 获取动态图片
                $sys_images_list = $helper->getSysImagesByUid([$v['id']],'1');
                $list[$k]['pic_list'] = [];
                if(!empty($sys_images_list)){
                    $list[$k]['pic_list'] = json_decode($sys_images_list['src'],true);
                }
            }else{
                if(count($helper->get_pic_src($v['content'])) >= 3 ){
                    $list[$k]['pic_list'] = [
                        $helper->get_pic_src($v['content'])[0],
                        $helper->get_pic_src($v['content'])[1],
                        $helper->get_pic_src($v['content'])[2]
                    ];
                }else{
                    $list[$k]['pic_list'] = $helper->get_pic_src($v['content']);
                }
            }
        }

        return $this->setReturnMsg('200',$list);
    }

    public function handleToRecommendRes()
    {
        $helper = new helper();
        $aid   = $this->data['param']['aid'];

        $ArticleModel = new Article();
        $list   = $ArticleModel->getAll(['id'=>['neq',$aid],'state'=>1,'type'=>1],1,5,'id,title,content');
        $list   = empty($list) ? array() : selectDataToArray($list);
        foreach ($list as $k => $v){
            $list[$k]['pic_list'] = count($helper->get_pic_src($v['content'])) > 0  ? $helper->get_pic_src($v['content'])[0] : [] ;
        }
        return $this->setReturnMsg('200',$list);
    }

    // 处理浏览操作
    public function handleToBrowseRes()
    {
        $model = new Article();
        try{
            if(!($u_res = $model->setUpdate(['state'=>1,'id'=>(int)$this->data['param']['aid']],'Inc','browse'))){
                return $this->setReturnMsg('103');
            }
            return $this->setReturnMsg('200',$u_res);
        }catch (Exception $e){
            return $this->setReturnMsg('502');
        }
    }

    // 处理评论操作
    public function handleToCommentRes()
    {
        $helper = new helper();
        $ArticleModel = new Article();
        $UserLikesModel = new UserComment();
        $SysMessageModel = new SysMessage();
        Db::startTrans(); //开启事务
        try{
            $setUserComment = $this->_getSetCommentData();
            $saveUserComment = $UserLikesModel->toAdd($setUserComment);
            if(!$saveUserComment){
                Db::rollback();
                return $this->setReturnMsg('104');
            }
            $setSysMessageData = $helper->getMessageData([
                'type'=>'2','type_id'=>$UserLikesModel->getLastInsID(),'content'=>'评论消息','title'=>'用户评论'
            ]);
            $saveSysMessage = $SysMessageModel->toAdd($setSysMessageData);
            if(!$saveSysMessage){
                Db::rollback();
                return $this->setReturnMsg('104');
            }

            $saveArticle = $ArticleModel->setUpdate(['state'=>1,'id'=>$this->data['param']['id']],'Inc','comments');
            if(!$saveArticle){
                Db::rollback();
                return $this->setReturnMsg('103');
            }

            if($saveUserComment && $saveArticle){
                Db::commit();
                return $this->setReturnMsg('200');
            }

        }catch (Exception $e){
            Db::rollback();
            return $this->setReturnMsg('502');
        }
    }

    public function handleToLikeRes()
    {
        $ArticleModel = new Article();
        $UserLikesModel = new UserLikes();
        Db::startTrans(); //开启事务
        try{
            $setUserLikes = $this->_getSetLikeData();
            $saveUserLikes = $UserLikesModel->toAdd($setUserLikes);
            if(!$saveUserLikes){
                Db::rollback();
                return $this->setReturnMsg('104');
            }

            $saveArticle = $ArticleModel->setUpdate(['state'=>1,'id'=>$this->data['param']['id']],'Inc','likes');
            if(!$saveArticle){
                Db::rollback();
                return $this->setReturnMsg('103');
            }

            if($setUserLikes && $saveArticle){
                Db::commit();
                return $this->setReturnMsg('200');
            }

        }catch (Exception $e){
            Db::rollback();
            return $this->setReturnMsg('502');
        }
    }

    public function handleToCommentListRes()
    {
        $helper = new helper();
        $UserCommentModel = new UserComment();
        $list = $UserCommentModel->getAll(
            ['state'=>1,'type'=>1,'type_id'=>$this->data['param']['id'],'examine'=>1],
            $this->data['param']['page'],'10','id,uid,content,created_at'
        );
        if(empty($list)){
            return $this->setReturnMsg('200',array());
        }
        $list = selectDataToArray($list);

        $UserEvent = new User();
        $all_user_id = array_unique(array_column($list,'uid'));
        $UserInfo = $UserEvent->setData(['uid'=>$all_user_id])->getAllUserList();

        foreach ($list as $k => $v) {
            $list[$k]['user_name'] = $UserInfo[$v['uid']]['name'];
            $list[$k]['user_src'] = $UserInfo[$v['uid']]['url'];
            $list[$k]['time'] = $helper->time_tran($v['created_at']);
        }
        return $this->setReturnMsg('200',$list);
    }

    public function handleToCreateRes()
    {
        Db::startTrans();
        try{
            $model = new Article();
            $setData = $this->_setAddData();
            if(!($res = $model->toAdd($setData))){
                return $this->setReturnMsg('104');
            }
            if(isset($this->data['params']['imgList'])){
                $save_image_data = [
                    'fun_id' => $model->getLastInsID(),
                    'src' => $this->data['params']['imgList'],
                    'fun_type' => $this->data['params']['type'] == 2 ? 1 : 2 ,
                    'state' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $sys_images_model = new SysImages();
                $add_sys_image = $sys_images_model->toAdd($save_image_data);
                if($res && $add_sys_image){
                    Db::commit();
                }else{
                    Db::rollback();
                    return $this->setReturnMsg('104');
                }
            }
            $this->_sendEmail($model->getLastInsID());
            Db::commit();
            return $this->setReturnMsg('200');
        }catch (Exception $e){
            Db::rollback();
            return $this->setReturnMsg('502');
        }
    }

    public function handleToNewListRes()
    {
        $helper = new helper();
        try{
            $page   = $this->data['param']['page'];
            $ArticleModel = new Article();
            $list   = $ArticleModel->getAll(['state'=>1,'examine'=>1],$page,10,'*','id desc');
            $list   = empty($list) ? array() : selectDataToArray($list);

            if(empty($list)){
                return $this->setReturnMsg('200');
            }

            // 获取列表相关用户信息
            $UserEvent = new User();
            $all_user_id = array_unique(array_column($list,'uid'));
            $UserInfo = $UserEvent->setData(['uid'=>$all_user_id])->getAllUserList();

            // 获取列表相关宠圈信息
            $circle_model  = new Circle();
            $circle_id_arr = array_unique(array_column($list,'circle_id'));
            $circle_list   = $circle_model->getAll(['status'=>1,'audit_status'=>1,'cid'=>['IN',$circle_id_arr]],0,count($circle_id_arr));
            $circleId2name = [];
            if(!empty($circle_list)){
                $circle_list = selectDataToArray($circle_list);
                foreach ($circle_list as $k => $v) {
                    $circleId2name[$v['cid']] = $v['name'];
                }
            }

            foreach ($list as $k => $v){
                $list[$k]['user']['nickname'] = $UserInfo[$v['uid']]['name'];
                $list[$k]['user']['avatar'] = $UserInfo[$v['uid']]['url'];
                $list[$k]['user']['id'] = $UserInfo[$v['uid']]['id'];
                $list[$k]['time'] = $helper->time_tran($v['time']);
                $list[$k]['circle_info'] = [
                    'circle_id'   => $v['circle_id'],
                    'circle_name' => isset($circleId2name[$v['circle_id']]) ? $circleId2name[$v['circle_id']] : '',
                ];
                if($v['type'] == 2){
                    // 获取动态图片
                    $sys_images_list = $helper->getSysImagesByUid([$v['id']],'1');
                    $list[$k]['pic_list'] = [];
                    if(!empty($sys_images_list)){
                        $list[$k]['pic_list'] = json_decode($sys_images_list['src'],true);
                    }
                }else{
                    if(count($helper->get_pic_src($v['content'])) >= 3 ){
                        $list[$k]['pic_list'] = [
                            $helper->get_pic_src($v['content'])[0],
                            $helper->get_pic_src($v['content'])[1],
                            $helper->get_pic_src($v['content'])[2]
                        ];
                    }else{
                        $list[$k]['pic_list'] = $helper->get_pic_src($v['content']);
                    }
                }
            }

            return $this->setReturnMsg('200',$list);
        }catch (Exception $e){
            $helper->SendEmail(
                "查询首页列表异常【异常时间:".date('Y-m-d H:i:s')."】",
                "查询首页列表异常,异常信息:".$e->getMessage()
            );
            return $this->setReturnMsg('502');
        }



    }

    protected function _setAddData()
    {
        return [
            'uid'       => $this->data['params']['uid'],
            'circle_id' => $this->data['params']['cid'],
            'type'      => $this->data['params']['type'],
            'content'   => isset($this->data['params']['content']) ? $this->data['params']['content'] : '',
            'abstract'  => isset($this->data['params']['abstract']) ? $this->data['params']['abstract'] : '' ,
            'time'      =>date('Y-m-d H:i:s'),
            'state'     => 1,
            'created_at'=>date('Y-m-d H:i:s')
        ];
    }

    // 获取添加评论数据
    protected function _getSetCommentData()
    {
        return [
            'uid'  => $this->data['param']['uid'],
            'type' => 1, //文章默认为1
            'type_id' => $this->data['param']['id'],
            'content' => $this->data['param']['content'],
            'created_at' => $this->data['created_at'],
            'state' => 1,
            'examine' => 0,
        ];
    }

    protected function _getSetLikeData()
    {
        return [
            'uid'  => $this->data['param']['uid'],
            'type' => 1, //文章默认为1
            'type_id' => $this->data['param']['id'],
            'created_at' => $this->data['created_at'],
            'state' => 1
        ];
    }

    protected function _sendEmail($id){
        $helper = new helper();
        // 拼接内容
        $title = date('Y-m-d H:i:s')."新增动态";
        $content = "用户ID为\t【".$this->data['params']['uid']."】的用户在." .date('Y-m-d H:i:s')."添加一条新的动态信息,请尽快审核！\t";
        $res = $helper->SendEmail($title,$content);
        if($res != '1'){
            writeLog(getWriteLogInfo('邮件异常','title:'.$title,'content:'.$content,'error'));
        }
    }


}
