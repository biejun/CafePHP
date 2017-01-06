<?php
if(!defined('IS_ANY'))exit('Access denied!');
/**
 *	UI界面可视化工具类
 *
 *	这里包含的方法都是将数据处理成友好的形式输出到页面上
 */
final class UIKit{

	# 格式化时间
	public static function formatTime($time){
		$dur=$_SERVER['REQUEST_TIME']-$time;
		if(dur == 0)return '刚刚';
		if($dur < 60)return $dur.'秒前';
		if($dur < 3600)return floor($dur/60).'分钟前';
		if($dur < 86400)return floor($dur/3600).'小时前';
		if($dur < 259200)return floor($dur/86400).'天前';
		return date('Y-m-d H:i',$time);
	}
	# 格式化大小
	public static function formatSize($filesize){
		$unit = array(' B', ' KB', ' MB', ' GB', ' TB');
	    for ($f = 0; $filesize >= 1024 && $f < 4; $f++){
	        $filesize /= 1024; 
	    }
	    return round($filesize, 2).$unit[$f];
	}
	# 文本截断 $string 要截取的字符串,$length 要截取的字数,$append 是否打印省略号移
	public static function truncate($string,$length,$append = true){
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
}