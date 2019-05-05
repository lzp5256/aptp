<?php
namespace app\activity\model;

use think\Model;

class Activity extends Model
{
    protected $table = 'activity';

    public function getActivityList($where,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->select();
    }

    public function getActivityPageList($where,$offset=0,$num=1,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }

    public function getOneActivityInfo($where,$field='*'){
        return $this->where($where)->field($field)->find();
    }

    public function getAddActivityRes($data){
        $this->data($data);
        return $this->save();
    }

    public function getSaveActivityRes($where,$data){
        return $this->where($where)->update($data);
    }


}