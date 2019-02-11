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
}