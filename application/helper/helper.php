<?php
namespace app\helper;

use app\base\controller\Base;
use app\dynamic\model\Dynamic;
use app\dynamic\model\DynamicComment as DynamicCommentModel;
use app\dynamic\model\DynamicLike;
use app\user\model\User;
use app\activity\model\Activity;

class helper extends Base {
    /** 注:公用方法首字母大写 */

    protected $data_type  = 1; //1-字符串(默认) 2-数组
    const EFFECTIVE_STATE = '1'; //有效状态值
    const INVALID_STATE   = '2'; //无效状态值

    /**
     * 公用方法 | 获取评论列表
     *
     * @return array
     */
    public function GetCommentList(){
        $where['status'] = '1';
        $where['did'] = $this->data['did'];  // 默认为字符串
        // 如果传入值为数组，则更换条件
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }

        $model = new DynamicCommentModel();
        $data  = selectDataToArray($model->where($where)->select());
        $list = [];
        foreach ($data as $k => $v){
            $list[$v['did']]['list'][] = $v;
        }
        return $list;
    }

    /**
     * 公用方法 | 检查用户是否有效
     *
     * return array
     */
    public function GetUserStatusById(){
        $uid = $this->data['uid'];
        $model = new User();
        $findUserInfo = $model->findUser(['id'=>(int)$uid,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findUserInfo)){
            return [];
        }
        return findDataToArray($findUserInfo);
    }

    /**
     * 公用方法 | 检查用户是否在规定动态内点赞状态
     *
     * return array
     */
    public function GetUserLikeState(){
//        $uid = $this->data['uid'];
//        $did = $this->data['did'];
        $where['did'] = $this->data['did'];
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }
        if($this->data_type == '1'){
            $where['uid'] = $this->data['uid'];
        }
        $model = new DynamicLike();
        $data = $model->where($where)->select();
        if(empty($data)){
            return [];
        }
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$v['uid']] = 1;
        }
        return $arr;
    }



    /**
     * 公用方法 | 获取动态详情
     *
     * return array
     */
    public function GetDynamicById(){
        $did = $this->data['did'];
        $model = new Dynamic();
        $findArticle = $model->findArticle(['id'=>(int)$did,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findArticle)){
            return [];
        }
        return findDataToArray($findArticle);
    }

    /**
     * 公用方法 | 获取活动信息
     *
     * return array
     */
    public function GetActivityInfoById(){
        $activity_id = $this->data['activity_id'];
        $model = new Activity();
        $getOneActivityInfo = $model->getOneActivityInfo(['id'=>(int)$activity_id,'status'=>self::EFFECTIVE_STATE]);
        if(empty($getOneActivityInfo)){
            return [];
        }
        return findDataToArray($getOneActivityInfo);
    }

}
