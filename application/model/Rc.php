<?php
namespace app\model;

use think\Model;

class Rc extends Model
{
    protected $table = 'rc';

    /**
     * 查找多条记录
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function getAll($where,$offset=0,$num=1,$field='*',$order='rc_id desc')
    {
        return $this->where($where)->field($field)->order($order)->limit("$offset,$num")->select();
    }

    /**
     * 查询单条记录
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function getOne($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * 添加
     *
     * @param $data
     *
     * @return mixed
     */
    public function toAdd($data)
    {
        $this->data($data);
        return $this->save();
    }

    /**
     * 更新
     *
     * @param $where
     * @param $data
     *
     * @return false|int
     */
    public function toUpdate($where,$data)
    {
        return $this->where($where)->update($data);
    }
}
