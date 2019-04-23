<?php
namespace app\dynamic\model;

use think\Model;

class DynamicComment extends Model
{
    // 设置当前模型对于的数据库名称
    protected $table = 'dynamic_comment';

    /**
     * 查找多条评论信息
     *
     * @param array $where 查询条件
     * @param string $field 查询字段 默认为全部
     * @param string $order 排序方式 默认id倒序
     * @param int $offset 查询页数
     * @param int $num 查询条
     *
     * @return array
     */
    public function getDynamicCommentList($where,$offset=0,$num=1,$field='*',$order='id desc')
    {
        return $this->where($where)->field($field)->order($order)->page("$offset,$num")->select();

    }

    /**
     * 查询一条评论信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findDynamicCommentInfo($where,$field='*')
    {
        return $this->where($where)->field($field)->find();

    }

    /**
     * 添加评论
     * @param $data
     * @return mixed
     */
    public function addDynamicComment($data)
    {
        $this->data($data);
        return $this->save();
    }
}