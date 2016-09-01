<?php
if(!defined('ABSPATH'))exit('Access denied!');

final class UIKit{
	# 网页提示框，支持跳转
	public static function alert($text,$url=''){
		echo"<script type='text/javascript'>";
		echo"alert('$text');";
		if($url!=''){
			echo"location.href='$url';";
		}else{
			echo"history.back();";
		}
		echo"</script>";
		exit;
	}
	# 格式化大小
	public static function format_size($filesize){
		if($filesize >= 1073741824){
			$filesize=round($filesize / 1073741824 * 100) / 100 . ' GB';
		}elseif ($filesize >= 1048576){
			$filesize=round($filesize / 1048576 * 100) / 100 . ' MB';
		}elseif($filesize >= 1024){
			$filesize=round($filesize / 1024 * 100) / 100 . ' KB';
		}else{
			$filesize=$filesize.' Bytes';
		}
		return $filesize;
	}
	# 二维数组排序
	public static function array_sort(array $array, $key, $asc = true){
		$result = array();
		# 整理出准备排序的数组
		foreach ($array as $k => &$v) {
			$values[$k] = isset($v[$key]) ? $v[$key] : '';
		}
		unset($v);
		# 对需要排序键值进行排序
		$asc ? asort($values) : arsort($values);
		foreach ($values as $k => $v) {
			$result[$k] = $array[$k];
		}
		return $result;
	}
	# 抽取多维数组中的某个键值，并返回一个一维数组
	public static function get_array_keys(array $array,$key){
		$res = array();
		if($array){
			foreach ($array as $v) {
				if(is_array($v) && isset($v[$key])){
					$res[] = $v[$key];
				}else{
					break;
				}
			}
		}
		return $res;
	}
	# 抽取多维数组中的第一个键值，并返回一个一维数组
	public static function get_array_shift(array $array){
		$res = array();
		if($array){
			$res = array_map('array_shift', $array);
		}
		return $res;
	}
	/** 
	 * 数组分页函数
	 * $count   每页多少条数据
	 * $page   当前第几页
	 * $array   查询出来的所有数组
	 * order false - 不变     true - 反序 
	 */
	public static function page_array($count,$page,$array,$order=false){
		$page=(empty($page))?'1':$page;
		$start=($page-1)*$count;
		if($order){
			$array=array_reverse($array);
		}
		$totals=count($array);
		$counts=ceil($totals/$count);#计算总页面数
		$pagedata=array();
		$pagedata=array_slice($array,$start,$count);
		return $pagedata;
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
	# 字符串加密解密 ENCODE为加密，DECODE为解密 expiry 过期时间
	public static function secure_core($string, $operation = 'DECODE',$key = VALIDATE, $expiry = 0){
		$key_length = 5;#随机密钥长度 取值 0-32
		$fixedkey = md5($key);
		$egiskeys = md5(substr($fixedkey, 16, 16));
		$runtokey = $key_length ? ($operation == 'DECODE' ? substr($string, 0, $key_length) : substr(md5(microtime(true)), -$key_length)) : '';
		$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
		$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));
		$i = 0;
		$result = '';
		$string_length = strlen($string);
		for ($i = 0; $i < $string_length; $i++) {
			$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
		}
		if($operation == 'ENCODE') {
			return $runtokey . str_replace('=', '', base64_encode($result));
		} else {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		}
	}
	# 创建文件夹
	public static function mk_dir($dir,$mode=0777) {
	    if(!is_dir($dir)) {
	       	self::mk_dir(dirname($dir));
	        mkdir($dir,$mode);
	    }
	}
	# 删除文件夹
	public static function rm_dir($dir){
	    $dh=opendir($dir);
	    while($file=readdir($dh)){
	        if($file!="."&&$file!=".."){
	            $fullpath=$dir."/".$file;
	            if(!is_dir($fullpath)){
	                unlink($fullpath);
	            }else{
	                rm_dir($fullpath);
	            }
	        }
	    }
	    closedir($dh);
	    if(rmdir($dir)){
	        return true;
	    }else{
	        return false;
	    }
	}
}