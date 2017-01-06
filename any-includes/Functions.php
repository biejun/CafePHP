<?php

# 是否登录
function is_login(){

	return Widget::get('admin@user')->isLogin();
}

# 是否为管理员
function is_admin(){

	return Widget::get('admin@user')->isAdmin();
}

# 取得多个$_GET或$_POST参数的值
function query_vars() {

	$vars = func_get_args();
	
	foreach ( (array) $vars as $var ) {
	
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
	
		return is_int( $def ) ? (int) $_GET[$var] : trim( $_GET[$var] );
	}
	
	return $def;
}
# 取得$_POST参数的值
function post_query_var( $var , $def = ''){
	
	if( isset( $_POST[$var] ) ){
	
		return is_int( $def ) ? (int) $_POST[$var] : $_POST[$var];
	}
	
	return $def;
}
/**
 * $_SESSION操作
 * @param string $key session名称 如果为空则取出全部session
 * @param mixed $value session值 如果为空则取出值 为null则删除
 * @return mixed
 */
function session( $key , $value = '' ){
	
	if( !isset( $key ) ) return $_SESSION;
	
	if( '' === $value ){
	
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	
	}elseif(is_null($value)){
	
		unset($_SESSION[$key]);
	}else{
	
		$_SESSION[$key] =  $value;
	}
}
function is_ssl() {
	
	if ( isset( $_SERVER['HTTPS'] ) ) {
	
		if ( 'on' == strtolower( $_SERVER['HTTPS'] ) )
	
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

#获取IP
function get_ip(){

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){

		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){

		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else{

		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
#新浪接口
function get_city(){

	$ip = get_ip();
	
	$json = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip);

	if(isset($json)){

		$address = json_decode($json,true);
		
		if($address!='-3'){
			return $address['province'].$address['city'];
		}
	}
	return '火星';
}
# 创建文件夹
function make_dir( $dir , $mode=0777 ) {
    
    if( !is_dir( $dir ) ) {
	
		make_dir( dirname( $dir ) );
	
		mkdir( $dir , $mode );
    }
}
# 删除文件夹
function remove_dir( $dir ){
	
	$dh=opendir( $dir );
	
	while( $file=readdir( $dh ) ){
	
		if( $file != "." && $file != ".." ){
	
			$fullpath = $dir."/".$file;
	
			if( !is_dir( $fullpath ) ){
	
				unlink( $fullpath );
	
			}else{
	
				remove_dir( $fullpath );
	
			}
		}
	
	}
	
	closedir( $dh );
	
	if( rmdir( $dir ) ){
	
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
function get_ext( $filename ){
	
	if( !empty( $filename ) ){
	
		$explode=explode( ".",strtolower( $filename ) );
	
		return end( $explode );
	}
}
# 是否为图片
function is_image( $filename ){
	
	$fileext = get_ext( $filename );
	
	if(in_array( $fileext,array( 'jpg','jpeg','gif','png','bmp') ) ){
	
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
function upload_move( $from, $target= '' ){
	
	if ( function_exists( "move_uploaded_file" ) ){
	
		if ( move_uploaded_file( $from, $target ) ){
	
			@chmod($target,0755);
	
			return true;
		}
	
	}elseif ( copy( $from, $target ) ){
	
		@chmod( $target,0755 );
	
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
	
	$key_length = 8;
	
	$fixedkey = md5($key);
	
	$egiskeys = md5(substr($fixedkey, 16, 16));
	
	$runtokey = $key_length ? ($operation == 'DECODE' ? substr($string, 0, $key_length) : substr(md5(microtime(true)), -$key_length)) : '';
	
	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode( substr( $string, $key_length ) );
	
	$i = 0;
	
	$result = '';
	
	$string_length = strlen($string);
	
	for ($i = 0; $i < $string_length; $i++) {
	
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	
	if($operation == 'ENCODE') {
	
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
	
		if( ( substr($result, 0, 10) == 0 || 
				substr($result, 0, 10) - time() > 0) &&
					 substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16 ) ) {
	
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
	
	if($order) $array=array_reverse($array);
	
	$totals=count($array);
	
	$counts=ceil($totals/$count);#计算总页面数
	
	$pagedata=array();
	
	$pagedata=array_slice($array,$start,$count);
	
	return $pagedata;
}