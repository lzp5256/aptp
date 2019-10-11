<?php

namespace app\event\Circle;

use app\base\controller\Base;
use app\helper\helper;
use app\model\Article;
use app\model\Circle;
use app\model\SysImages;
use app\model\UserCircle;
use app\user\event\User;
use think\Exception;

class CircleHandles extends Base
{
    protected $sys_fun_type_ad = '1';

    public function handleToCircleListRes()
    {
        $cid = $this->data['params']['cid'];    // 宠圈ID
        $type = $this->data['params']['type'];  // 类型 | 1 = 推荐 2=其他
        $user_id = $this->data['params']['user_id']; // 登录用户ID

        $circleModel = new Circle();
        $list = $circleModel->getAll(['status' => 1, 'audit_status' => 1], '1', '1000', '*', 'cid asc');
        $circleList2Arr = empty($list) ? array() : selectDataToArray($list);
        // 处理数据
        $leftData = $rightData = array();
        if (!empty($circleList2Arr)) {
            foreach ($circleList2Arr as $k => $v) {
                $sid[] = $v['sid'];
            }
            // 获取图片信息
            $sysModel = new SysImages();
            $imgList = $sysModel->getAll(['id' => ['IN', array_unique($sid)], 'state' => 1, 'fun_type' => '3'], '0', count($sid));
            $img2Array = empty($imgList) ? [] : selectDataToArray($imgList);
            if (!empty($img2Array)) {
                foreach ($img2Array as $k => $v) {
                    // fun_type为3时，只有一张图片
                    $id2src[$v['id']] = json_decode($v['src'], true)[0];
                }
            }

            foreach ($circleList2Arr as $k => $v) {
                $circleList2Arr[$k]['src'] = '';
                if (!empty($id2src)) {
                    $circleList2Arr[$k]['src'] = isset($id2src[$v['sid']]) && !empty($id2src[$v['sid']]) ? $id2src[$v['sid']] : '';
                }
            }
        }

        foreach ($circleList2Arr as $k => $v) {
            if ($type == '1') {
                if ($v['pid'] == 0) {
                    $leftData[] = $v;
                }
                if ($v['pid'] != 0 && $v['recommend'] == 1) {
                    $rightData[] = $v;
                }
            } else {
                if ($v['pid'] == $cid) {
                    $rightData[] = $v;
                }
            }
        }

        // 获取登录用户关注宠圈列表
        $user_circle_data = [];
        $user_circle_model = new UserCircle();
        $user_circle_list = $user_circle_model->getAllList(['status' => 1, 'uid' => $user_id]);

        if (!empty($user_circle_list)) {
            $user_circle_data = selectDataToArray($user_circle_list);
        }
        $user_circle_id_arr = array_unique(array_column($user_circle_data, 'target')); // 用户关注的宠圈数组

        if (!empty($rightData)) {
            foreach ($rightData as $k => $v) {
                $rightData[$k]['is_join'] = 2;
                foreach ($user_circle_id_arr as $v1) {
                    if ($v['cid'] == $v1) {
                        $rightData[$k]['is_join'] = 1;
                    }
                }
            }
        }

        $data = [
            'leftList' => $leftData,
            'rightList' => $rightData,
        ];

        return $this->setReturnMsg('200', $data);
    }

    public function handleToRecommendRes()
    {
        $helper = new helper();
        try {
            $circleModel = new Circle();
            $list = $circleModel->getAll(['status' => 1, 'audit_status' => 1, 'lv' => 2, 'recommend' => '1'], '0', '10', '*', 'sort asc');
            $circleList2Arr = empty($list) ? array() : selectDataToArray($list);
            if (!empty($circleList2Arr)) {
                foreach ($circleList2Arr as $k => $v) {
                    $sid[] = $v['sid'];
                }
                // 获取图片信息
                $sysModel = new SysImages();
                $imgList = $sysModel->getAll(['id' => ['IN', array_unique($sid)], 'state' => 1, 'fun_type' => '3'], '0', count($sid));
                $img2Array = empty($imgList) ? [] : selectDataToArray($imgList);
                if (!empty($img2Array)) {
                    foreach ($img2Array as $k => $v) {
                        // fun_type为3时，只有一张图片
                        $id2src[$v['id']] = json_decode($v['src'], true)[0];
                    }
                }

                foreach ($circleList2Arr as $k => $v) {
                    $circleList2Arr[$k]['src'] = '';
                    if (!empty($id2src)) {
                        $circleList2Arr[$k]['src'] = $id2src[$v['sid']];
                    }
                }
            }
            return $this->setReturnMsg('200', $circleList2Arr);
        } catch (Exception $e) {
            $helper->SendEmail(
                "查询首页推荐宠圈异常【异常时间:" . date('Y-m-d H:i:s') . "】",
                "查询首页推荐宠圈异常,异常信息:" . $e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

    // 加入宠圈操作
    public function handleToJoinCircleRes()
    {
        $helper = new helper();
        try {
            $user_circle_model = new UserCircle();
            $getInfo = $user_circle_model->getOne([
                'uid' => $this->data['params']['user_id'],
                'target' => $this->data['params']['circle_id'],
                'status' => 1,
            ]);
            if (!empty($getInfo)) {
                $helper->SendEmail(
                    "用户宠圈列表异常【异常时间:" . date('Y-m-d H:i:s') . "】",
                    "用户【" . $this->data['params']['user_id'] . "】宠圈异常,异常信息:用户已加入ID为【target:" . $this->data['params']['circle_id'] . "】的宠圈"
                );
                return $this->setReturnMsg('200');
            }

            $data = [
                'uid' => $this->data['params']['user_id'],
                'target' => $this->data['params']['circle_id'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $res = $user_circle_model->toAdd($data);
            if (!$res) {
                return $this->setReturnMsg('104');
            }
            return $this->setReturnMsg('200', ['uc_id' => $user_circle_model->getLastInsID()]);
        } catch (Exception $e) {
            $helper->SendEmail(
                "用户加入宠圈异常【异常时间:" . date('Y-m-d H:i:s') . "】",
                "用户【" . $this->data['params']['user_id'] . "】加入宠圈异常,异常信息:" . $e->getMessage()
            );
            return $this->setReturnMsg('502');
        }
    }

    // 宠圈详情
    public function handleToCircleInfoRes()
    {
        $helper = new helper();
        try {
            $circle_model = new Circle();
            $circle_info = $circle_model->getOne([
                'status' => 1,
                'audit_status' => 1,
                'cid' => (int)$this->data['params']['circle_id'],
            ]);
            if (empty($circle_info)) {
                $helper->SendEmail(
                    "【用户ID:" . $this->data['params']['user_id'] . "】" . "获取宠圈详情异常。异常时间:" . date('Y-m-d H:i:s') . "】",
                    "【异常信息:Cid=" . $this->data['params']['circle_id'] . "不存在!请尽快处理～】"
                );
                return $this->setReturnMsg('600003');
            }

            // 获取宠圈图片
            $sys_image_model = new SysImages();
            $img_info = $sys_image_model->getOne([
                'state' => 1,
                'fun_type' => 3,
                'id' => $circle_info['sid'],
            ]);
            if (!empty($img_info)) {
                $circle_info['bg'] = json_decode($img_info['src'], true)[0];
            }
            return $this->setReturnMsg('200', $circle_info);
        } catch (Exception $e) {
            $helper->SendEmail(
                "【用户ID:" . $this->data['params']['user_id'] . "】" . "获取宠圈详情异常。异常时间:" . date('Y-m-d H:i:s') . "】",
                "【异常信息:" . $e->getMessage() . ",请尽快处理～】"
            );
            return $this->setReturnMsg('502');
        }
    }

    // 宠圈内容列表
    public function handleToCircleDetailRes()
    {
        $helper = new helper();
        try {
            $article_model = new Article();
            $article_list = $article_model->getAll(
                ['circle_id' => $this->data['params']['circle_id'], 'state' => 1, 'type' => 2, 'examine' => 1],
                $this->data['params']['page'],
                10,
                '*',
                'id desc'
            );
            if (empty($article_list)) {
                return $this->setReturnMsg('200');
            }
            $article_list = selectDataToArray($article_list);
            $article_uid_list = array_unique(array_column($article_list, 'uid'));

            // 获取列表对应的用户信息
            $UserEvent = new User();
            $UserInfo = $UserEvent->setData(['uid' => $article_uid_list])->getAllUserList();

            foreach ($article_list as $k => $v) {
                // 获取动态图片
                $sys_images_list = $helper->getSysImagesByUid([$v['id']],'1');
                $article_list[$k]['pic_list'] = [];
                if(!empty($sys_images_list)){
                    $article_list[$k]['pic_list'] = json_decode($sys_images_list['src'],true);
                }
                $article_list[$k]['user']['name'] = $UserInfo[$v['uid']]['name'];
                $article_list[$k]['user']['src']  = $UserInfo[$v['uid']]['url'];
            }

            return $this->setReturnMsg('200',$article_list);
        } catch (Exception $e) {
            $helper->SendEmail(
                "【获取宠圈详情列表异常。异常时间:".date('Y-m-d H:i:s')."】",
                "【异常信息:" . $e->getMessage() . ",请尽快处理～】"
            );
            return $this->setReturnMsg('502');
        }
    }


}
