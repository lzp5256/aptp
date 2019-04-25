<?php
namespace app\dynamic\model;

use think\Model;

class DynamicLike extends Model
{
    protected $table = 'dynamic_likes';

    public function addDynamicLikes($data = array()){
        $this->data($data);
        return $this->save();
    }
}