<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/2
 * Time: 2:34 PM
 */
namespace app\behavior;

class UserTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run(){
        return 'hell UserTest';
    }
}