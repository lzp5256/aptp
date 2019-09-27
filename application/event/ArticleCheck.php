<?php
namespace app\event;

use app\base\controller\Base;
use app\model\Article;
use app\model\User;
use app\model\UserLikes;

class ArticleCheck extends Base
{
    protected $data = [];

    public function checkToInfoParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToListParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['page']) || !isset($param['page'])){
            return $this->setReturnMsg('400002');
        }
        $this->data['param']['page'] = (int)$param['page'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToRecommendParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }

        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToBrowseParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }
        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToCommentParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }
        if(empty($param['id']) || !isset($param['id'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['id'] = (int)$param['id'];

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        if(empty($param['content']) || !isset($param['content'])){
            return $this->setReturnMsg('400003');
        }
        if(mb_strlen($param['content'])<5){
            return $this->setReturnMsg('400004');
        }
        $this->data['param']['content'] = (string)trim($param['content']);

        $UserModel = new User();
        $UserInfo  = $UserModel->getOne(['status'=>1,'id'=>$param['uid']]);
        if(empty($UserInfo)){
            return $this->setReturnMsg('105');
        }
        $this->data['user_info'] = findDataToArray($UserInfo);

        $ArticleModel = new Article();
        $ArticleInfo  = $ArticleModel->getOne(['state'=>1,'id'=>$param['id']]);
        if(empty($ArticleInfo)){
            return $this->setReturnMsg('400005');
        }
        $this->data['article_info'] = findDataToArray($ArticleInfo);
        $this->data['created_at']  = date('Y-m-d H:i:s');
        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToLikeParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }
        if(empty($param['id']) || !isset($param['id'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['id'] = (int)$param['id'];

        if(empty($param['uid']) || !isset($param['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['param']['uid'] = (int)$param['uid'];

        $UserLikeModel = new UserLikes();
        $CheckUserLike = $UserLikeModel->getOne([
            'state'=>1,'type'=>1,'type_id'=>$param['id'],'created_at'=>['gt',date('Y-m-d',time())]
        ]);
        $CheckUserLike = empty($CheckUserLike) ? [] : findDataToArray($CheckUserLike);
        if(!empty($CheckUserLike)){
            return $this->setReturnMsg('400006');
        }
        $UserModel = new User();
        $UserInfo  = $UserModel->getOne(['status'=>1,'id'=>$param['uid']]);
        if(empty($UserInfo)){
            return $this->setReturnMsg('105');
        }
        $this->data['user_info'] = findDataToArray($UserInfo);

        $ArticleModel = new Article();
        $ArticleInfo  = $ArticleModel->getOne(['state'=>1,'id'=>$param['id']]);
        if(empty($ArticleInfo)){
            return $this->setReturnMsg('400005');
        }
        $this->data['article_info'] = findDataToArray($ArticleInfo);
        $this->data['created_at']  = date('Y-m-d H:i:s');
        return $this->setReturnMsg('200',$this->data);

    }

    public function checkToCommentListParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('100');
        }
        if(empty($param['id']) || !isset($param['id'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['id'] = (int)$param['id'];

        if(empty($param['page']) || !isset($param['page'])){
            return $this->setReturnMsg('400002');
        }
        $this->data['param']['page'] = (int)$param['page'];

        $ArticleModel = new Article();
        $ArticleInfo  = $ArticleModel->getOne(['state'=>1,'id'=>$param['id']]);
        if(empty($ArticleInfo)){
            return $this->setReturnMsg('400005');
        }
        $this->data['article_info'] = findDataToArray($ArticleInfo);
        $this->data['created_at']  = date('Y-m-d H:i:s');
        return $this->setReturnMsg('200',$this->data);

    }

    public function checkToCreateParams($params)
    {

        if(!is_array($params) || empty($params)){
            return $this->setReturnMsg('100');
        }
        if(empty($params['uid']) || !isset($params['uid'])){
            return $this->setReturnMsg('101');
        }
        $this->data['params']['uid'] = (int)$params['uid'];

        if(empty($params['cid']) || !isset($params['cid'])){
            return $this->setReturnMsg('600002');
        }
        $this->data['params']['cid'] = (int)$params['cid'];

        if(empty($params['content']) ){
            return $this->setReturnMsg('400008');

        }
        if(mb_strlen($params['content']) > 200){
            return $this->setReturnMsg('400011');
        }
        $this->data['params']['content'] = (string)$params['content'];

        if(!isset($params['imgList']) || empty($params['imgList']) || $params['imgList'] == '[]' ){
            return $this->setReturnMsg('400009');
        }

        if(count(json_decode($params['imgList'],true)) < 1){
            return $this->setReturnMsg('400010');
        }

        $this->data['params']['imgList'] = (string)$params['imgList'];

//        if(empty($params['title'])){
//            return $this->setReturnMsg('400007');
//        }
//        $this->data['params']['title'] = (string)$params['title'];

        $this->data['params']['type'] = 2;

//        if(!empty($params['act_id']) && $params['act_id']>0){
//            $this->data['params']['type'] = 3;
//            $this->data['params']['act_id'] = $params['act_id'];
//        }

        $this->data['params']['abstract'] = '-';



        return $this->setReturnMsg('200',$this->data);
    }
}