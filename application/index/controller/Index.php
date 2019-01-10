<?php
namespace app\index\controller;

use app\base\controller\Base;

class Index extends Base
{
    public function __construct()
    {
        $check = parent::__construct();
        if($check != true){
            return json($check);
        }
    }


}
