<?php
namespace app\release\event;

use app\base\controller\Base;

class Handle extends Base
{
    /**
     * @desc 发布问答处理
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseQaRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        return $Result;
    }
}
