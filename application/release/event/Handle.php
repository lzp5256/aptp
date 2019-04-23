<?php
namespace app\release\event;

use app\base\controller\Base;
use app\dynamic\model\DynamicComment;
use app\user\model\User;
use app\qa\model\Qa;
use app\dynamic\model\Dynamic;
use think\Request;

class Handle extends Base
{
    protected $log_level = 'error';

    /**
     * @desc 发布问答处理
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseQaRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10065';
            $Result['errMsg'] = '错误码[L10065]';
            return $Result;
        }
        $qaModel = new Qa();
        $saveRes = $qaModel->addQa($this->_getAddQaData());
        if(!$saveRes){
            $Result['errCode'] = 'L10066';
            $Result['errMsg'] = '错误码[L10066]';
            return $Result;
        }
        return $Result;
    }

    /**
     * @desc 发布文章处理
     * @date 2019.04.18
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseArticleRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10071';
            $Result['errMsg'] = '错误码[L10071]';
            return $Result;
        }
        $model = new Dynamic();
        $saveRes = $model->addArticle($this->_getAddArticleData());
        if(!$saveRes){
            $Result['errCode'] = 'L10072';
            $Result['errMsg'] = '错误码[L10072]';
            return $Result;
        }
        return $Result;
    }

    public function handleReleaseCommentRes(){
        $Result = ['errCode' => '200', 'errMsg'  => '发布成功', 'data' => []];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10076';
            $Result['errMsg'] = '抱歉,系统异常,未查询到用户信息';
            writeLog(getWriteLogInfo('发布评论,验证用户异常',json_encode($this->data),$userModel->getLastSql()),$this->log_level);
            return $Result;
        }
        // 验证动态是否存在
        $dymanicModel = new Dynamic();
        $findInfo = $dymanicModel->findArticle(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findInfo)){
            $Result['errCode'] = 'L10077';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog((getWriteLogInfo('发布评论,验证动态异常',json_encode($this->data),$dymanicModel->getLastSql())),$this->log_level);
            return $Result;
        }
        $commentModel = new DynamicComment();
        $addComment = $commentModel->addDynamicComment($this->_getAddCommentData());
        if(!$addComment){
            $Result['errCode'] = 'L10078';
            $Result['errMsg'] = '错误码[L10072]';
            writeLog(getWriteLogInfo('发布评论,储存数据异常',json_encode($this->_getAddCommentData()),$commentModel->getLastSql()),$this->log_level);
            return $Result;
        }
        return $Result;
    }

    protected function _getAddQaData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'title'     => $this->data['param']['title'],
            'pic_url'   => isset($this->data['param']['upload']) ? $this->data['param']['upload'] : '' ,
            'pet_type'  => $this->data['param']['pet_type'],
            'QA_type'   => $this->data['param']['QA_type'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }

    protected function _getAddArticleData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'title'     => $this->data['param']['title'],
            'cover'     => $this->data['param']['cover'],
            'content'   => $this->data['param']['content'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }

    protected function _getAddCommentData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'did'       => $this->data['param']['did'],
            'content'   => $this->data['param']['content'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }
}
