<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Article;
use app\model\SysImages;
use app\model\UserComment;
use app\model\UserLikes;
use app\user\event\User;
use app\helper\helper;
use think\Db;

class ArticleHandles extends Base
{
    protected $sys_fun_type_ad = '1';

    public function handleToInfoRes()
    {
        $helper = new helper();
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
        return $this->setReturnMsg('200',$info);
    }

    public function handleToListRes()
    {
        $helper = new helper();

        $page   = $this->data['param']['page'];

        $ArticleModel = new Article();
        $list   = $ArticleModel->getAll(['state'=>1],$page,10);
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

    public function handleToCommentRes()
    {
        $ArticleModel = new Article();
        $UserLikesModel = new UserComment();
        Db::startTrans(); //开启事务
        try{
            $setUserComment = $this->_getSetCommentData();
            $saveUserComment = $UserLikesModel->toAdd($setUserComment);
            if(!$saveUserComment){
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
            ['state'=>1,'type'=>1,'type_id'=>$this->data['param']['id']],
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
                    'fun_id' => $this->data['params']['uid'],
                    'src' => $this->data['params']['imgList'],
                    'fun_type' => 1,
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
            $this->_sendEmail($res);
            Db::commit();
            return $this->setReturnMsg('200');
        }catch (Exception $e){
            Db::rollback();
            return $this->setReturnMsg('502');
        }
    }

    protected function _setAddData()
    {
        return [
            'uid'   => $this->data['params']['uid'],
            'title' => $this->data['params']['title'],
            'type'  => $this->data['params']['type'],
            'content'  => $this->data['params']['content'],
            'abstract' => $this->data['params']['abstract'],
            'time'  =>date('Y-m-d H:i:s'),
            'state' => 1,
            'created_at'=>date('Y-m-d H:i:s')
        ];
    }

    protected function _getSetCommentData()
    {
        return [
            'uid'  => $this->data['param']['uid'],
            'type' => 1, //文章默认为1
            'type_id' => $this->data['param']['id'],
            'content' => $this->data['param']['content'],
            'created_at' => $this->data['created_at'],
            'state' => 1
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
        $content = "用户ID为\t【".$this->data['params']['uid']."】的用户在." .date('Y-m-d H:i:s')."添加一条新的动态信息【ID:".$id."】,请尽快审核！\t";
        $res = $helper->SendEmail($title,$content);
        if($res != '1'){
            writeLog(getWriteLogInfo('邮件异常','title:'.$title,'content:'.$content,'error'));
        }
    }


}
