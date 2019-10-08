<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/1/16
 * Time: 6:13 PM
 */
namespace app\qiniu\controller;

use Qiniu\Auth;
use think\Config;

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
        $config = Config::get('qiniu');
        //用于签名的公钥和私钥
        $AccessKey = $config['AccessKey'];
        $SecretKey = $config['SecretKey'];
        // 初始化签权对象
        $auth = new Auth($AccessKey,$SecretKey);
        // 空间名  https://developer.qiniu.io/kodo/manual/concepts
        $bucket = 'muyao-pet';
        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        return json(['uptoken'=>$token]);
    }
}