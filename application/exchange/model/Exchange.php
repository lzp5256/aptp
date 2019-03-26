<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/26
 * Time: 3:14 PM
 */
namespace app\exchange\model;

use think\model;
class Exchange extends Model
{
    protected $table = 'exchange';

    /**
     * @desc 查询兑换列表
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function selectExchange($where,$offset=0,$num=1,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->limit("$offset,$num")->select();
    }
}