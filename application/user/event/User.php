<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/8
 * Time: 5:34 PM
 */
namespace app\user\event;

use app\base\controller\Base;

class User extends Base {

    public function test()
    {
        return 'test';
    }

    public function getUserToken()
    {
        var_dump($this->data);die;
    }

}