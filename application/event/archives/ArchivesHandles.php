<?php
namespace app\event\archives;

use app\base\controller\Base;
use app\model\Archives;

class ArchivesHandles extends Base
{
    public function handleToCreate()
    {
        return 1;
    }

    public function handleToList()
    {
        $archives_model = new Archives();
        $list = $archives_model->getAll(['state'=>1,'uid'=>$this->data['param']['uid']]);
        if(empty($list)){
            return $this->setReturnMsg('200');
        }
        return $this->setReturnMsg('200',selectDataToArray($list));
    }
}
