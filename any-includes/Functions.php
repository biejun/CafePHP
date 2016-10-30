<?php
if( !defined('ABSPATH') ) exit('Access denied!');

# 系统函数扩展

/**
 *	系统行为扩展
 *	
 *	类似于wordpress的功能函数
 */

$GLOBALS['any_actions'] = array();
/**
 *	挂载一个函数到特定的行为中
 *
 *	@param string $action 一个已定义的行为钩子，当钩子函数执行时，$function同时执行
 *	@param function $function 当前需要挂载的函数
 *	@return 无返回值
 *
 **/
function add_action($action,$function){
	$guid = to_guid_string($function);
	if(!isset($GLOBALS['any_actions'][$action][$guid]))
		$GLOBALS['any_actions'][$action][$guid] = $function;
}
/**
 *	执行某个特定行为钩子的函数
 *
 *	@param string $action 一个已定义的行为钩子
 *	@return 直接调用，无返回值
 *
 **/
function do_action($action){
	$args = array_slice(func_get_args(), 1);
	if (isset($GLOBALS['any_actions'][$action]))
	foreach ($GLOBALS['any_actions'][$action] as $function)
		if(!is_null($function))
		call_user_func_array($function,$args);
}
/**
 *	执行某个特定行为钩子的函数
 *
 *	@param string $action 一个已定义的行为钩子
 *	@return mixed 有返回值，返回所有挂载在此类钩子上return的值
 *
 **/
function apply_action($action,$value){
	$args = func_get_args();
	if (isset($GLOBALS['any_actions'][$action]))
	foreach ($GLOBALS['any_actions'][$action] as $function)
		if(!is_null($function)){
			$args[1] = $value;
			$value = call_user_func_array($function,array_slice($args,1));
		}
	return $value;
}

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
		$_SESSION[$key] =  $value;
	}
}
# 调用应用程序数据模型
function widget($widget=''){
	static $_widgets = array();
	if(empty($widget)){
		if(isset($_widgets['widget'])){
			return $_widgets['widget'];
		}else{
			$_widgets['widget'] = new Widget();
			return $_widgets['widget'];
		}
	}
	$info = array($widget,'widget');
	$instance = $widget;
	if(strpos($widget,':')!==false){
		$instance = str_replace(':', '_', $widget);
		$info = explode(':', $widget);
	}
	$instance .= '_widget';
	if(isset($_widgets[$instance]))
		return $_widgets[$instance];
	$widget_file = ANYAPP .$info[0].'/widgets/'.$info[0].'.'.$info[1].'.php';
	if(is_file( $widget_file )){
		require( $widget_file );
		$_widgets[$instance] = new $instance($info[0]);
		return $_widgets[$instance];
	}else{
		throw new Exception('没有找到应用"'.$info[0].'"的"'.$info[1].'"组件');
	}
}
function is_ssl() {
	if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
		if ( '1' == $_SERVER['HTTPS'] )
			return true;
	} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}
# 获取当前页面地址
function get_page_url(){
	$url = is_ssl() ? "https://" : "http://";
	$url .= $_SERVER["SERVER_NAME"];
	if( $_SERVER["SERVER_PORT"] != "80" ) {
		$url .= ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	}else{
	    $url .= $_SERVER["REQUEST_URI"];
	}
	return $url;
}
# 创建文件夹
function make_dir($dir,$mode=0777) {
    if( !is_dir($dir) ) {
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
 * 获取文件后缀名
 *
 * @param	string $filename 文件名
 * @return	string
 */
function get_ext($filename){
	if(!empty($filename)){
		$explode=explode(".",strtolower($filename));
		return end($explode);
	}
}
# 是否为图片
function is_image($filename){
	$fileext = get_ext($filename);
	if(in_array($fileext,array('jpg','jpeg','gif','png','bmp'))){
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
/** 
 * 字符串加密解密
 * @param $string  需要加密的字符串
 * @param $operation  ENCODE - 加密 DECODE - 解密
 * @param $key 混淆加密
 * @param $expiry 过期时间
 */
function secure_core($string, $operation = 'DECODE',$key = VALIDATE, $expiry = 0){
	$key_length = 5;#密钥长度 取值 0-32
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
 * @param $count   每页多少条数据
 * @param $page   当前第几页
 * @param $array   查询出来的所有数组
 * @param $order false - 不变     true - 反序 
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