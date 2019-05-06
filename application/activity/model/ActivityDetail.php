<?php
namespace app\activity\model;

use think\Model;

class ActivityDetail extends Model
{
    protected $table = 'activity_detail';

    public function getActivityDetailList($where,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->select();
    }

    public function getActivityDetailPageList($where,$offset=0,$num=1,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }

    public function getOneActivityDetailInfo($where,$field='*'){
        return $this->where($where)->field($field)->find();
    }

    public function getAddActivityDetailRes($data){
        $this->data($data);
        return $this->save();
    }

    public function getSaveActivityDetailRes($where,$data){
        return $this->where($where)->update($data);
    }
}