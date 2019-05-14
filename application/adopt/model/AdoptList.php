<?php
namespace app\adopt\model;

use think\Model;

class AdoptList extends Model
{
    protected $table = 'adopt_list';

    public function getAdoptList($where,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->select();
    }

    public function getAdoptPageList($where,$offset=0,$num=1,$field='*',$order='id desc'){
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }

    public function getOneAdoptInfo($where,$field='*'){
        return $this->where($where)->field($field)->find();
    }

    public function getAddAdoptRes($data){
        $this->data($data);
        return $this->save();
    }

    public function getUpdateAdoptRes($where,$data){
        return $this->where($where)->update($data);
    }
}
