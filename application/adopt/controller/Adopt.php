<?php
namespace app\adopt\controller;

use app\adopt\event\Check;
use app\adopt\event\Handle;
use app\base\controller\Base;

class Adopt extends Base
{
    /**
     * 添加新的领养记录
     * @desc 索引值为0,1,2......
     * @param  int    $uid 用户id
     * @param  array  $imgList 上传的图片数组
     * @param  string $name 昵称
     * @param  string $age 年龄索引
     * @param  int    $sex 性别索引
     * @param  int    $type 类别索引
     * @param  int    $charge 是否收费索引
     * @param  int    $source 来源索引
     * @param  int    $shape 体型索引
     * @param  int    $hair 毛发索引
     * @param  int    $vaccine 疫苗索引
     * @param  int    $sterilization 绝育索引
     * @param  int    $insectRepellent 驱虫索引
     * @param  string $condition 领养条件
     * @param  string $describe 描述
     * @param  string $wechat 微信
     * @param  string $phone  手机号码
     * @param  string $city 城市
     * @param  string $address 详细地址
     * @param  int    $wShow 展示微信索引
     * @param  int    $pShow 展示手机索引
     * @param  int    $message 展示消息通知索引
     * @return array
     */
    public function getAddAdoptRes(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => '发布成功,审核通过后会显示在首页',
            'data'    => [],
        ];
        $param = request()->post();

        $check_event = new Check();
        if(($check_res = $check_event->setData(['param'=>$param])->CheckAddParam()) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        $handle_event = new Handle();
        if(($check_res = $handle_event->setData($check_res['data'])->handleAddAdopt()) && $check_res['errCode'] != '200'){
            return json($check_res);
        }
        return json($return_res);
    }


    public function getAdoptDetailRes(){
        $return_res = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $param = request()->post();

        $check_event = new Check();
        if(($check_res = $check_event->setData(['param'=>$param])->CheckAdoptDetailParam()) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->handleAdoptDetailRes()) && $handle_res['errCode'] != '200'){
            return json($handle_res);
        }
        return json($return_res);

    }
}