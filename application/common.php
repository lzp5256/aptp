<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @desc 封装var_dump
 */
function vp($data){
    echo '<pre>';
    var_dump($data);
    echo '<pre/>';
    die;
}

/**
 * @desc 封装 curl 的调用接口，post的请求方式
 * @param string $url 访问地址
 * @param array|string $request 请求参数
 * @param int $timeout 时间
 * @param string $method 请求方式 不填为GET
 * @return mixed
 */
function sendCurlRequest($url,$request='',$method='',$timeout = 5){
    $log = [];
    $curl_index=time().rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    $log['curl_arr'][$curl_index]['url'] = $url;
    $log['curl_arr'][$curl_index]['request_data'] = $request;
    $log['curl_arr'][$curl_index]['header'] = [];
    $log['curl_arr'][$curl_index]['method'] = empty($method) ? 'GET' : 'POST';
    $log['curl_arr'][$curl_index]['time_start']=date('Y-m-d H:i:s');
    $con = curl_init();
    curl_setopt($con, CURLOPT_URL,$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    // 默认为Get请求Post请求为true
    if($method){
        curl_setopt($con, CURLOPT_POSTFIELDS, http_build_query($request));
        curl_setopt($con, CURLOPT_POST,true);
    }

    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    $output = curl_exec($con);
    $log['curl_arr'][$curl_index]['time_end']=date('Y-m-d H:i:s');
    if($error=curl_error($con)){
        $log['curl_arr'][$curl_index]['error']=$error;
        die($error);
    }
    curl_close($con);
    $log['curl_arr'][$curl_index]['result']=$output;
    \think\Log::write(json($log),'info');
    return $output;
}

/**
 * @desc 验证手机号是否正确
 * @param string $phone 手机号
 * @return bool
 */
function isMobile($phone){
    $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
    if ( preg_match( $search, $phone ) ) {
        return ( true );
    } else {
        return ( false );
    }
}

/**
 * @desc 类型转换
 * @param $str 类型编号
 * @return mixed
 */
function strToType($str){
    $data = ['1'=>'狗', '2'=>'猫', '3'=>'其他'];
    return $data[$str];
}

/**
 * @desc  json转换数组
 * @param array $strArr 需要转换的json字符串
 * @return array
 */
function strToJson($strArr){
    $data = [];
    foreach ($strArr as $k => $v){
        if(empty($v)){
            $data[$k] = [];
        }else{
            $data[$k] = json_decode($v,true);
        }
    }
    return $data;
}

/**
 * 递归实现无限极分类
 * @param $array 分类数据
 * @param $pid 父ID
 * @param $level 分类级别
 * @return $list 分好类的数组 直接遍历即可 $level可以用来遍历缩进
 */

function getTree($array, $pid =1, $level = 1){

    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    static $list = [];
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['parent_id'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $value['rg_level'] = $level;
            //把数组放到list中
            $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            getTree($array, $value['rg_id'], $level+1);

        }
    }
    return $list;
}

function generateTree($array){
    //第一步 构造数据
    $items = array();
    foreach($array as $value){
        $items[$value['rg_id']] = $value;
    }
    //第二部 遍历数据 生成树状结构
    $tree = array();
    foreach($items as $key => $value){
        if(isset($items[$value['parent_id']])){
            $items[$value['parent_id']]['son'][] = &$items[$key];
        }else{
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}


