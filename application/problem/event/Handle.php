<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/3/5
 * Time: 4:45 PM
 */
namespace app\problem\event;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use app\user\model\User;

class Handle
{

    public function handle($data)
    {
        $Result = [
            'errCode' => '200',
            'errMsg' => 'success',
            'data' => [],
        ];
        if(empty($data['param']['title'])){
            $title = '问题反馈';
        }
        $model = new User();
        $user = $model->findUser(['id'=>$data['param']['uid']]);
        if(empty($user)){
            $Result['errMsg'] = '抱歉,未找到相关用户,请检查是否登录!';
            $Result['errCode'] = 'L10042';
            return $Result;
        }
        // 拼接内容
        $content = "【".date('Y-m-d H:i:s')."】\t\t 用户ID:\t".$user->id."。用户姓名为:\t" . base64_decode($user->name) . "。用户反馈信息为:\t".$data['param']['content'];
        $res = $this->_send($title,$content);
        if($res != '1'){
            $Result['errMsg'] = $res;
            $Result['errCode'] = 'L10044';
            return $Result;
        }
        return $Result;
    }

    protected function _send($title,$content)
    {
        $mail = new PHPMailer();
        try {
            // 服务器设置
            $mail->SMTPDebug = 0; // 开启Debug
            $mail->isSMTP(); // 使用SMTP
            $mail->Host = config('email.host'); // 服务器地址
            $mail->SMTPAuth = true; // 开启SMTP验证
            $mail->Username = config('email.uname'); // SMTP 用户名（你要使用的邮件发送账号）
            $mail->Password = config('email.pwd'); // SMTP 密码
            $mail->SMTPSecure = 'ssl'; // 开启TLS 可选
            $mail->Port = 465; // 端口
            // 设置发送的邮件的编码
            $mail->CharSet = 'UTF-8';

            // 收件人
            $mail->setFrom('reminder@yipinchongke.com'); // 来自
            $mail->addAddress('support@yipinchongke.com'); // 可以只传邮箱地址

            // 内容
            $mail->isHTML(true); // 设置邮件格式为HTML
            $mail->Subject = (string)$title; //邮件主题
            $mail->Body = (string)$content; //邮件内容
            $res = $mail->send();
            return $res;
        } catch (Exception $e) {
            return '邮件发送失败,Mailer Error:'.$mail->ErrorInfo;
        }
    }

}