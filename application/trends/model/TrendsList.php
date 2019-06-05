<?php
namespace app\trends\model;

use think\Model;

class TrendsList extends Model
{
    protected $table = 'trends_list';

    public function getTrendsList($where,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->select();
    }

    public function getTrendsPageList($where,$offset=0,$num=1,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }

    public function getOneTrendsInfo($where,$field='*'){
        return $this->where($where)->field($field)->find();
    }

    public function getAddTrendsRes($data){
        $this->data($data);
        return $this->save();
    }

    public function getUpdateTrendsRes($where,$data){
        return $this->where($where)->update($data);
    }
}
