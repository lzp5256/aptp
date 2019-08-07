<?php
namespace app\helper;

use app\base\controller\Base;
use app\dynamic\model\Dynamic;
use app\dynamic\model\DynamicComment as DynamicCommentModel;
use app\dynamic\model\DynamicLike;
use app\other\model\OtherFlag;
use app\region\model\Region;
use app\user\model\User;
use app\activity\model\Activity;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class helper extends Base {
    /** 注:公用方法首字母大写 */

    protected $data_type  = 1; //1-字符串(默认) 2-数组
    const EFFECTIVE_STATE = '1'; //有效状态值
    const INVALID_STATE   = '2'; //无效状态值

    /**
     * 公用方法 | 获取评论列表
     *
     * @return array
     */
    public function GetCommentList(){
        $where['status'] = '1';
        $where['did'] = $this->data['did'];  // 默认为字符串
        $where['action'] = $this->data['action'];
        // 如果传入值为数组，则更换条件
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }

        $model = new DynamicCommentModel();
        $data  = selectDataToArray($model->where($where)->select());
        if(empty($data)){
            return [];
        }
        $list = [];
        foreach ($data as $k => $v){
            $list[$v['did']]['list'][] = $v;
        }
        return $list;
    }

    /**
     * 公用方法 | 检查用户是否有效
     *
     * return array
     */
    public function GetUserStatusById(){
        $uid = $this->data['uid'];
        $model = new User();
        $findUserInfo = $model->findUser(['id'=>(int)$uid,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findUserInfo)){
            return [];
        }
        return findDataToArray($findUserInfo);
    }

    /**
     * 公用方法 | 检查用户是否在规定动态内点赞状态
     *
     * return array
     */
    public function GetUserLikeState(){
//        $uid = $this->data['uid'];
//        $did = $this->data['did'];
        $where['did'] = $this->data['did'];
        $where['action'] = $this->data['action'];
        if(is_array($this->data['did'])){
            $this->data_type = 2;
            $where['did'] = ['IN',$this->data['did']];
        }
        if($this->data_type == '1'){
            $where['uid'] = $this->data['uid'];
        }
        $model = new DynamicLike();
        $data = $model->where($where)->select();
        if(empty($data)){
            return [];
        }
        $arr = [];
        foreach ($data as $k => $v){
            $arr[$v['uid']] = 1;
        }
        return $arr;
    }



    /**
     * 公用方法 | 获取动态详情
     *
     * return array
     */
    public function GetDynamicById(){
        $did = $this->data['did'];
        $model = new Dynamic();
        $findArticle = $model->findArticle(['id'=>(int)$did,'status'=>self::EFFECTIVE_STATE]);
        if(empty($findArticle)){
            return [];
        }
        return findDataToArray($findArticle);
    }

    /**
     * 公用方法 | 获取活动信息
     *
     * return array
     */
    public function GetActivityInfoById(){
        $activity_id = $this->data['activity_id'];
        $model = new Activity();
        $getOneActivityInfo = $model->getOneActivityInfo(['id'=>(int)$activity_id,'status'=>self::EFFECTIVE_STATE]);
        if(empty($getOneActivityInfo)){
            return [];
        }
        return findDataToArray($getOneActivityInfo);
    }

    /**
     * 公用方法 | 获取所有标签信息
     *
     * return array
     */
    public function GetFlagList(){
        $arr = [];
        $model = new OtherFlag();
        $data = $model->getOtherFlagList(['flagState'=>'1']);
        if(empty($data)){
            return [];
        }
        $data = selectDataToArray($data);
        foreach ($data  as $k => $v){
            $arr[explode(':',$v['flagName'])[3]] = explode(':',$v['flagValue']);
        }
        return $arr;
    }

    /**
     * 公用方法 | 发送邮件
     */
    public function SendEmail($title,$content){
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

    /**
     * 公用方法 | 获取地区名称
     *
     */
    public function GetCityByCode($code,$level){
        $model = new Region();
        $info  = $model->findRegion(['status'=>1,'rg_id'=>$code,'delete_flag'=>0,'rg_level'=>$level]);
        if(empty($info)){
            return [];
        }
        $res = $info->toArray();
        var_dump($res);die;
//        return
    }

    public function RcType($type){
        switch ($type){
            case 1:
                return [
                    'name' => '干垃圾/其他垃圾',
                    'describe' => '干垃圾即其它垃圾，指除可回收物、有害垃圾、厨余垃圾（湿垃圾）以外的其它生活废弃物。其他垃圾危害较小，但无再次利用价值，如建筑垃圾类，生活垃圾类等，一般采取填埋、焚烧、卫生分解等方法，部分还可以使用生物解决，如放蚯蚓等。是可回收垃圾、厨余垃圾、有害垃圾剩余下来的一种垃圾。如日常生活中遇到成分复杂、不易分离归集的物品，建议作为干垃圾/其他垃圾处理。',
                    'type'=>'餐巾纸、卫生间用纸、尿不湿、猫砂、狗尿垫、污损纸张、烟蒂、干燥剂、污损塑料、尼龙制品、编织袋、防碎气泡膜、大骨头、硬贝壳、硬果壳（椰子壳、榴莲壳、核桃壳、玉米衣、甘蔗皮）、硬果实（榴莲核、菠萝蜜核)、毛发、灰土、炉渣、橡皮泥、太空沙、带胶制品（胶水、胶带）、花盆、毛巾、一次性餐具、镜子、陶瓷制品、竹制品（竹篮、竹筷、牙签）、成分复杂的制品（伞、笔、眼镜、打火机）',
                    'src' =>'http://images.yipinchongke.com/rfw.png',
                ];
                break;
            case 2:
                return [
                    'name' => '湿垃圾/厨余垃圾/易腐垃圾',
                    'describe' => '湿垃圾又称为厨余垃圾，即易腐垃圾，指食材废料、剩菜剩饭、过期食品、瓜皮果核、花卉绿植、中药药渣等易腐的生物质生活废弃物。湿垃圾是居民日常生活及食品加工、饮食服务、单位供餐等活动中产生的垃圾，包括丢弃不用的菜叶、剩菜、剩饭、果皮、蛋壳、茶渣、骨头、湿巾、动物内脏、鱼鳞、树叶、杂草等，其主要来源为家庭厨房、餐厅、饭店、食堂、市场及其他与食品加工有关的行业。',
                    'type'=>'食材废料：谷物及其加工食品、肉蛋及其加工食品（包括动物内脏、蛋壳）、水产及其加工食品（鱼、鱼鳞、虾、虾壳、鱿鱼）、蔬菜（绿叶菜、根茎蔬菜、菌菇）、调料、酱料、剩菜剩饭：火锅汤底（沥干后的固体废弃物）、鱼骨、碎骨、茶叶渣、咖啡渣、过期食品风干食品、粉末类食品（冲泡饮料、面粉）、宠物饲料、瓜皮果核、花卉植物：家养绿植、花卉、花瓣、枝叶、中药药渣',
                    'src' =>'http://images.yipinchongke.com/hfw.png',
                ];
                break;
            case 3:
                return [
                    'name' => '可回收垃圾',
                    'describe' => '可回收垃圾就是可以再生循环的垃圾。本身或材质可再利用的纸类、硬纸板、玻璃、塑料、金属、塑料包装，与这些材质有关的如：报纸、杂志、广告单及其它干净的纸类等皆可回收。',
                    'type'=>'主要包括废纸、塑料、玻璃、金属和纺织物五大类生活垃圾，有害垃圾、电子/电器垃圾和电池三类特殊危害垃圾以及废弃家具类垃圾。',
                    'src' =>'http://images.yipinchongke.com/rw.png',
                ];
                break;
            case 4:
                return [
                    'name' => '有害垃圾',
                    'describe' => '有害垃圾指废电池、废灯管、废药品、废油漆及其容器等对人体健康或者自然环境造成直接或者潜在危害的生活废弃物。分类投放有害垃圾时，应注意轻放。其中：废灯管等易破损的有害垃圾应连带包装或包裹后投放；废弃药品宜连带包装一并投放；杀虫剂等压力罐装容器，应排空内容物后投放；在公共场所产生有害垃圾且未发现对应收集容器时，应携带至有害垃圾投放点妥善投放。',
                    'type'=>'常见包括废电池、废荧光灯管、废灯泡、废水银温度计、废油漆桶、过期药品等。有害有毒垃圾需特殊正确的方法安全处理（包括废电池、废日光灯管、废水银温度计、过期药品等，这些垃圾需要特殊安全处理）。',
                    'src' =>'http://images.yipinchongke.com/hw.png',
                ];
                break;

            default:
                return [];
        }
    }

    // 处理时间
    function time_tran($the_time)
    {
        $now_time = date("Y-m-d H:i:s", time());
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        return floor($dur / 86400) . '天前';
                    }
                }
            }
        }
    }

    // 从富文本中提取图片地址
    function get_pic_src($content)
    {
        $pageContents = str_replace('\"','"',$content);
        $reg = '/<img (.*?)+src=[\'"](.*?)[\'"]/i';
        preg_match_all( $reg , $pageContents , $results );
        return $results[2];
    }
}
