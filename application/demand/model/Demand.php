<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/11
 * Time: 3:42 PM
 */
namespace app\demand\model;

use think\Model;

class Demand extends Model
{
    // 设置当前模型对于的数据库名称
    protected $table = 'demand';

    /**
     * @desc 添加需求
     * @param array $data
     * @return false|int
     */
    public function addDemand($data)
    {
        $this->data($data);
        return $this->save();
    }

    /**
     * 查找用户信息
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectDemand($where,$offset,$num,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();
    }
}