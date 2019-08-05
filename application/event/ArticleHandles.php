<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Article;
use app\user\event\User;
use app\helper\helper;

class ArticleHandles extends Base
{
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

        return $this->setReturnMsg('200',$list);
    }

    public function handleToRecommendRes()
    {
        $helper = new helper();
        $aid   = $this->data['param']['aid'];

        $ArticleModel = new Article();
        $list   = $ArticleModel->getAll(['id'=>['neq',$aid],'state'=>1],1,5,'id,title,content');
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


}
