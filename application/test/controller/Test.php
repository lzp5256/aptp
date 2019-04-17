<?php
namespace app\test\controller;


class Test
{
    public function decode(){
        $name = trim(request()->post('name'));
        echo base64_decode($name);
    }
}