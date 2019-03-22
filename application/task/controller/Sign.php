<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/22
 * Time: 3:28 PM
 */
namespace app\task\controller;

class Sign
{
    public function __construct()
    {
        $result = parent::__construct();
        if($result['errCode'] != '200'){
            echo $result['errMsg'];die;
        }
    }

    public function sign()
    {

    }
}