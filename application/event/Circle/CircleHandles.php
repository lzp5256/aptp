<?php
namespace app\event\Circle;

use app\base\controller\Base;
use app\helper\helper;
use app\model\Circle;
use app\model\SysImages;
use think\Exception;

class CircleHandles extends Base
{
    protected $sys_fun_type_ad = '1';

    public function handleToCircleListRes()
    {
        $cid = $this->data['params']['cid'];
        $type = $this->data['params']['type'];

        $circleModel = new Circle();
        $list = $circleModel->getAll(['status'=>1,'audit_status'=>1],'1','1000','*','cid asc');
        $circleList2Arr = empty($list) ? array() : selectDataToArray($list);
        // 处理数据
        $leftData = $rightData = array();
        if(!empty($circleList2Arr)){
            foreach ($circleList2Arr as $k => $v){
                $sid[] = $v['sid'];
            }
            // 获取图片信息
            $sysModel = new SysImages();
            $imgList = $sysModel->getAll(['id'=>['IN',array_unique($sid)],'state'=>1,'fun_type'=>'3'],'0',count($sid));
            $img2Array = empty($imgList) ? [] : selectDataToArray($imgList);
            if(!empty($img2Array)){
                foreach ($img2Array as $k => $v){
                    // fun_type为3时，只有一张图片
                    $id2src[$v['id']] = json_decode($v['src'],true)[0];
                }
            }

            foreach ($circleList2Arr as $k => $v){
                $circleList2Arr[$k]['src'] = '';
                if(!empty($id2src)){
                    $circleList2Arr[$k]['src'] = isset($id2src[$v['sid']]) && !empty($id2src[$v['sid']]) ? $id2src[$v['sid']] : '';
                }
            }
        }

        foreach ($circleList2Arr as $k => $v){
            if($type == '1' ){
                if($v['pid'] == 0){
                    $leftData[] = $v;
                }
                if($v['pid'] != 0 && $v['recommend'] == 1){
                    $rightData[] = $v;
                }
            }else{
                if($v['pid'] == $cid){
                    $rightData[] = $v;
                }
            }
        }

        $data = [
            'leftList'  => $leftData,
            'rightList' => $rightData,
        ];

        return $this->setReturnMsg('200',$data);
    }

    public function handleToRecommendRes()
    {
        $helper = new helper();
        try{
            $circleModel = new Circle();
            $list = $circleModel->getAll(['status'=>1,'audit_status'=>1,'lv'=>2,'recommend'=>'1'],'0','10','*','sort asc');
            $circleList2Arr = empty($list) ? array() : selectDataToArray($list);
            if(!empty($circleList2Arr)){
                foreach ($circleList2Arr as $k => $v){
                    $sid[] = $v['sid'];
                }
                // 获取图片信息
                $sysModel = new SysImages();
                $imgList = $sysModel->getAll(['id'=>['IN',array_unique($sid)],'state'=>1,'fun_type'=>'3'],'0',count($sid));
                $img2Array = empty($imgList) ? [] : selectDataToArray($imgList);
                if(!empty($img2Array)){
                    foreach ($img2Array as $k => $v){
                        // fun_type为3时，只有一张图片
                        $id2src[$v['id']] = json_decode($v['src'],true)[0];
                    }
                }

                foreach ($circleList2Arr as $k => $v){
                    $circleList2Arr[$k]['src'] = '';
                    if(!empty($id2src)){
                        $circleList2Arr[$k]['src'] = $id2src[$v['sid']];
                    }
                }
            }
            return $this->setReturnMsg('200',$circleList2Arr);
        }catch (Exception $e){
            $helper->SendEmail(
                "查询首页推荐宠圈异常【异常时间:".date('Y-m-d H:i:s')."】",
                "查询首页推荐宠圈异常,异常信息:".$e->getMessage()
            );
        }
    }


}
