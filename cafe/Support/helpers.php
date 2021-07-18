<?php
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link 	 https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

use Cafe\Foundation\App;
use Cafe\Foundation\Model;

if (! function_exists('app')) {
    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return App::getInstance();
        }
        return App::getInstance()->make($abstract);
    }
}

// 页面路径
function u($url = '')
{
    return PATH .$url;
}

// 用一个函数来调用models
function model($name)
{
    return Model::load($name);
}

// 获取IP地址
function getIp()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}
// 删除文件夹
function removeDir($dir)
{
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir."/".$file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                removeDir($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}
// 格式化大小
function formatSize($filesize)
{
    $size = sprintf('%u', $fileSize);
    if ($size == 0) {
        return '0 Bytes';
    }
    $sizename = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
    
    return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).$sizename[$i];
}
// 获取位置（新浪接口）
function getCity($defaultCity = '火星')
{
    $ip = getIp();
    if ($ip === '::1') {
        return $defaultCity;
    }
    $json = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip);
    if (isset($json)) {
        $address = json_decode($json, true);
        if ($address !== -3) {
            return $address['province'].$address['city'];
        }
    }
    return $defaultCity;
}
function isWin()
{
    return strtoupper(substr(PHP_OS, 0, 3)) == "WIN";
}

// CURL获取文件内容
function fetch( $url, $timeout = 10, $method = 'GET', $params=array() )
{
    $curl = curl_init();
    $result = '';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    if (substr($url, 0, 8) == 'https://'){
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    }
    if (strtoupper($method) == 'POST') {
        $postData = is_array($params)?http_build_query($params):$params;
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    }
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
# 百度地图GPS位置
function baiduGPS($location, $ak)
{
    $res = '';
    if(!empty($ak) && !empty($location)){
        $params=array(
            'coordtype' => 'wgs84ll',//坐标的类型: bd09ll 百度经纬度坐标 gcj02ll 国测局经纬度坐标 wgs84ll GPS经纬度
            'location' => $location,
            'ak' => $ak,
            'output' => 'json'
            );
        $result = fetch('http://api.map.baidu.com/geocoder/v2/',10,'POST',$params);
        $data = json_decode($result,true);
        if($data['status']===0){
            $info = $data['result']['addressComponent'];
            $res = $info['province'];
            $res .= $info['city'];
            $res .= $info['district'];
            $res .= $info['street'];
        }
    }
    return trim($res);
}

// 文本截断 $string 要截取的字符串,$length 要截取的字数,$append 是否打印省略号移
function truncate($string,$length,$append = true){
    $string = trim($string);
    $strlength = strlen($string);
    if ($length == 0 || $length >= $strlength){
        return $string;
    }elseif ($length < 0){
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }
    if (function_exists('mb_substr')){
        $newstr = mb_substr($string, 0, $length,"UTF-8");
    }elseif (function_exists('iconv_substr')){
        $newstr = iconv_substr($string, 0, $length,"UTF-8");
    }else{
        for($i=0;$i<$length;$i++){
                $tempstring=substr($string,0,1);
                if(ord($tempstring)>127){
                    $i++;
                    if($i<$length){
                        $newstring[]=substr($string,0,3);
                        $string=substr($string,3);
                    }
                }else{
                    $newstring[]=substr($string,0,1);
                    $string=substr($string,1);
                }
            }
        $newstr =join($newstring);
    }
    if ($append && $string != $newstr){
        $newstr .= '...';
    }
    return $newstr;
}

// 格式化时间戳
function formatTime($time){
    $dur=$_SERVER['REQUEST_TIME']-$time;
    if($dur === 0)return '刚刚';
    if($dur < 60)return $dur.' 秒前';
    if($dur < 3600)return floor($dur/60).' 分钟前';
    if($dur < 86400)return floor($dur/3600).' 小时前';
    if($dur < 2592000)return floor($dur/86400).' 天前';
    return date('m月d日',$time);
}
/**
 * 加密函数
 * @param   string  $str    加密前的字符串
 * @param   string  $key    密钥
 * @return  string  加密后的字符串
 */
function encode($str, $key = HASH) {
    $tmp       = '';
    $keylength = strlen($key);
    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
        $tmp .= substr($str, $i, $keylength) ^ $key;
    }
    return str_replace('=', '', base64_encode($tmp));
}
/**
 * 解密函数
 * @param   string  $str    加密后的字符串
 * @param   string  $key    密钥
 * @return  string  加密前的字符串
 */
function decode($str, $key = HASH) {
    $tmp       = '';
    $keylength = strlen($key);
    $str       = base64_decode($str);
    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength) {
        $tmp .= substr($str, $i, $keylength) ^ $key;
    }
    return $tmp;
}