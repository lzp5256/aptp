<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/25
 * Time: 4:10 PM
 */
namespace app\apply\model;

use think\Model;

class Apply extends Model
{
    protected $table = 'apply';

    /**
     * @desc 添加申请
     * @param array $data 数据
     * @return false|int
     */
    public function addApply($data)
    {
        $this->data($data);
        return $this->save();
    }
}