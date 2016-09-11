<?php
if( !defined('ABSPATH') ) exit('Access denied!');

# 系统函数扩展

# 取得多个$_GET或$_POST参数的值
function query_vars( $vars ) {
	foreach ((array) $vars as $var ) {
		if ( empty( $_POST[ $var ] ) ) {
			if ( empty( $_GET[ $var ] ) ) {
				$vars[ $var ] = '';
			} else {
				$vars[ $var ] = $_GET[ $var ];
			}
		} else {
			$vars[ $var ] = $_POST[ $var ];
		}
	}
	return $vars;
}
# 取得$_GET参数的值
function get_query_var( $var , $def = ''){
	if( isset( $_GET[$var] ) ){
		return is_int($def) ? (int) $_GET[$var] : trim($_GET[$var]);
	}
	return $def;
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
	if (is_object($mix)) {
		return spl_object_hash($mix);
	} elseif (is_resource($mix)) {
		$mix = get_resource_type($mix) . strval($mix);
	} else {
		$mix = serialize($mix);
	}
	return md5($mix);
}
/**
 * $_SESSION操作
 * @param string $key session名称 如果为空则取出全部session
 * @param mixed $value session值 如果为空则取出值 为null则删除
 * @return mixed
 */
function session( $key , $value = ''){
	if( '' === $key ) return $_SESSION;
	if( '' === $value ){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}elseif(is_null($value)){
		unset($_SESSION[$key]);
	}else{
		$_SESSION[$name]  =  $value;
	}
}
# 调用应用程序数据模型
function model($app='',$name='class'){
	static $_model = array();
	if(empty($app)){
		if(isset($_model['model'])){
			return $_model['model'];
		}else{
			$_model['model'] = new Model($app);
			return $_model['model'];
		}
	}
	$attribute = ('class'==$name)?'model':$name;
	$instance = $app.'_'.$attribute;
	if(isset($_model[$instance]))
		return $_model[$instance];
	if(is_file( ANYAPP .$app.'/'.$app.'.'.$name.'.php')){
		require( ANYAPP .$app.'/'.$app.'.'.$name.'.php');
		$_model[$instance] = new $instance($app);
		return $_model[$instance];
	}else{
		throw new Exception('没有找到"'.$app.'"的"'.$file.'"文件');
	}
}
# 创建文件夹
function make_dir($dir,$mode=0777) {
    if(!is_dir($dir)) {
		make_dir(dirname($dir));
		mkdir($dir,$mode);
    }
}
# 删除文件夹
function remove_dir($dir){
	$dh=opendir($dir);
	while($file=readdir($dh)){
		if($file!="."&&$file!=".."){
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)){
				unlink($fullpath);
			}else{
				remove_dir($fullpath);
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
/**
 * 移动上传文件
 *
 * @param	string	$from	文件来源
 * @param	string	$target 移动目标地
 * @return	boolean
 */
function upload_move($from, $target= ''){
	if (function_exists("move_uploaded_file")){
		if (move_uploaded_file($from, $target)){
			@chmod($target,0755);
			return true;
		}
	}elseif (copy($from, $target)){
		@chmod($target,0755);
		return true;
	}
	return false;
}
# 字符串加密解密 ENCODE为加密，DECODE为解密 expiry 过期时间
function secure_core($string, $operation = 'DECODE',$key = VALIDATE, $expiry = 0){
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
# 生成指定长度随机KEY
function get_random_key($n = 18){
	return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_~'), 0, $n);
}
# 二维数组排序
function array_sort(array $array, $key, $asc = true){
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
function get_array_keys(array $array,$key){
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
function get_array_shift(array $array){
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
function page_array($count,$page,$array,$order=false){
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