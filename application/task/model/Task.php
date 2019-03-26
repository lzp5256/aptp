<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/23
 * Time: 4:17 PM
 */
namespace app\task\model;

use think\model;

class Task extends Model
{
    protected $table = 'task';

    /**
     * @desc 获取所有任务信息
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectTask($where,$offset=0,$num=1,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->limit("$offset,$num")->select();
    }

    /**
     * 查询一条任务信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findTask($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }
}