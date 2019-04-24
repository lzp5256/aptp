<?php
namespace app\helper;

use app\base\controller\Base;
use app\dynamic\model\DynamicComment as DynamicCommentModel;

class helper extends Base {

    protected $data_type = 1; //1-字符串(默认) 2-数组

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

}
