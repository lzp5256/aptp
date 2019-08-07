<?php
namespace app\controllers\controller;

use app\base\controller\Base;
use app\event\ArticleCheck;
use app\event\ArticleHandles;

class Article extends Base
{
    public function toInfo()
    {
        $res = [
            'errCode' => '200',
            'errMsg'  => '成功',
            'data'    => [],
        ];
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToInfoParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToInfoRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }
        $res['data'] = $handles_res['data'];
        return json($res);
    }

    public function toList()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToListParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToListRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    public function toRecommend()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToRecommendParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToRecommendRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));

    }

    public function toBrowse()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToBrowseParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToBrowseRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    public function toComment()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToCommentParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCommentRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    public function toLike()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToLikeParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToLikeRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }

    public function toCommentList()
    {
        $param = request()->post();
        $check_event   = new ArticleCheck();
        $handles_event = new ArticleHandles();

        if(($check_res = $check_event->checkToCommentListParams($param)) && $check_res['errCode'] != '200'){
            return json($check_res);
        }

        if(($handles_res = $handles_event->setData($check_res['data'])->handleToCommentListRes()) && $handles_res['errCode'] != '200'){
            return json($handles_res);
        }

        return json($this->setReturnMsg('200',$handles_res['data']));
    }
}