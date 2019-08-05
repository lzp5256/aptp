<?php
namespace app\event;

use app\base\controller\Base;

class ArticleCheck extends Base
{
    protected $data = [];

    public function checkToInfoParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('502');
        }

        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToListParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('502');
        }

        if(empty($param['page']) || !isset($param['page'])){
            return $this->setReturnMsg('400002');
        }
        $this->data['param']['page'] = (int)$param['page'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToRecommendParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('502');
        }

        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }

    public function checkToBrowseParams($param)
    {
        if(empty($param) || !is_array($param)){
            return $this->setReturnMsg('502');
        }
        if(empty($param['aid']) || !isset($param['aid'])){
            return $this->setReturnMsg('400001');
        }
        $this->data['param']['aid'] = (int)$param['aid'];

        return $this->setReturnMsg('200',$this->data);
    }
}