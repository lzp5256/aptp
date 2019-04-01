<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/4/1
 * Time: 4:58 PM
 */
namespace app\exchange\event;

use app\base\controller\Base;
use app\exchange\model\Exchange as ExchangeModel;

class Exchange extends Base
{
    /**
     * @desc 处理查询兑换详情页面数据
     * @author salted.fish
     * @date 2019.04.01
     * @return array
     */
    public function handle_detail()
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => '查询成功',
            'data'    => [],
        ];
        $model = new ExchangeModel();
        $findExchangeInfo = $model->findExchange(['id'=>(int)$this->data['param']['eid'],'status'=>'1']);

        if(empty($findExchangeInfo)){
            $Result['data'] = [];
            return $Result;
        }

        $Result['data'] = $findExchangeInfo->toArray();

        return $Result;
    }
}