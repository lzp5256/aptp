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