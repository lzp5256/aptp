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
        var_dump($list);die;
    }
}
