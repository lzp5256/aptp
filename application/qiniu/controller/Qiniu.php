<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/16
 * Time: 6:13 PM
 */
namespace app\qiniu\controller;

use Qiniu\Auth;

class Qiniu
{
    /**
     * 获取七牛储存所需要的token
     * @date 2019/01/16
     * @return json
     */
    public function getQiniuToken()
    {
        import('qiniu.autoload',VENDOR_PATH);
        //用于签名的公钥和私钥
        $AccessKey = config('Qiniu.accessKey');
        $SecretKey = config('Qiniu.secretKey');
        // 初始化签权对象
        $auth = new Auth($AccessKey,$SecretKey);
        // 空间名  https://developer.qiniu.io/kodo/manual/concepts
        $bucket = 'pet-lizhipeng';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        return json(['uptoken'=>$token]);
    }
}