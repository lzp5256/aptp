<?php
namespace app\qa\model;

use think\Model;

class Qa extends Model
{
    // 设置当前模型对于的数据库名称
    protected $table = 'qa_demand';

    /**
     * 查询一条问答信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findQa($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * add qa
     * @param $data
     * @return mixed
     */
    public function addQa($data)
    {
        $this->data($data);
        return $this->save();
    }
}
