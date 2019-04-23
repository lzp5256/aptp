<?php
namespace app\release\controller;

use app\base\controller\Base;
use app\release\event\Check;
use app\release\event\Handle;
use think\Request;

class Comment extends Base
{
    protected $log_level = 'error';
    /**
     * @desc 发布评论
     * @date 2019.04.23
     * @author lizhipeng
     * @return json
     */
    public function release(){
        $Result = ['errCode' => '200', 'errMsg'  => '发布成功', 'data' => []];
        $param = request()->post('');
        $check_event = new Check();
        if(($check_res = $check_event->checkCommentParam($param)) && $check_res['errCode']!='200'){
            return json($check_res);
        }
        $handle_event = new Handle();
        if(($handle_res = $handle_event->setData($check_res['data'])->handleReleaseCommentRes()) && $handle_res['errCode']!='200'){
            return json($handle_res);
        }
        return json($Result);
    }

}