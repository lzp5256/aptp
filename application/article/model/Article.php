<?php
namespace app\article\model;

use think\Model;

class Article extends Model
{
    // 设置当前模型对于的数据库名称
    protected $table = 'article_demand';

    /**
     * 查询一条文章信息
     *
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function findArticle($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    /**
     * add 文章
     * @param $data
     * @return mixed
     */
    public function addArticle($data)
    {
        $this->data($data);
        return $this->save();
    }
}
