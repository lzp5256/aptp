<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 11:45 AM
 */
namespace app\behavior;

class Base
{
    public function __construct()
    {
        if(request()->isPost() == true){
            return '111';
        }
    }
}