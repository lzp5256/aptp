<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 3:12 PM
 */
namespace app\base\controller;

class Base
{
    public function __construct()
    {
        // 判断是否是post请求
        if(request()->isPost() != true){
            return 'request is not post';
        }

        // 验证token
    }
}