<?php

if (!function_exists('showError')) {
	/**
	 * 系统错误信息
	 *
	 * @param  string $severity 错误级别
	 * @param  string $msg      错误信息
	 * @param  int $line     错误行号
	 * @param  string $file    错误文件路径
	 */
    function showError($severity,$msg,$line,$file){

        $hash = substr(md5($line),3,7);

        $log = '<p>'.sprintf('%s %s','<strong>'.$severity.'：</strong>',$msg).'</p>';
        $log .= '<p>'.sprintf('Line %s：%s',$line,$file).'</p>';

        $html = "<style>*{margin:0;padding:0}
            .error_{$hash}{padding:20px;background:#505050;font-size:14px;}
            .error_{$hash}>p{line-height:1.777;color:#e0e0e0;margin:0;}
            .error_{$hash}>p>strong{color:#e49631;}
            </style><div class='error_{$hash}'>{$log}</div>";

        if (!headers_sent()) {

            header("Content-type: text/html; charset=" . G( 'system','charset' ) );

            header('HTTP/1.1 500 Internal Server Error');
        }
        
        echo $html;
    }

    set_error_handler(function($severity, $msg, $file, $line){
        showError('['.$severity.']',$msg,$line,$file);
    });

    set_exception_handler(function($e){
        showError('捕获异常',$e->getMessage(),$e->getLine(),$e->getFile());
    });
}

if (!function_exists('W')) {
	# 打印出漂亮的数组
	function W ($widget){

		return \Coffee\Foundation\Widget::instance($widget);
	}
}

if (!function_exists('G')) {
	/**
	 * 获取任何全局配置部分或单个设置值
	 *
	 * @param $section string 全局配置分组名
	 * @param $value string 分组类的某个变量
	 * @param $update string 更新某个值 例如：禁用debug G('system', 'debug', true);
	 */
	function G ($section = false, $value = false, $update = null) {

		static $_G;

        if($_G === null ){

            $files = ['config/system.php', 'config/database.php'];

            foreach ($files as $file)
            {
                if(file_exists($file)){
                    $_G = array_replace_recursive( (array) $_G
                        , include ($file) );
                }else{
                    throw new \Exception("没有找到".$file."配置文件");
                }
            }
        }

		// 读取或配置
		if ( $section && $value ) {

			if ($update !== null) {
				$_G[$section][$value] = $update;
			}
			return isset($_G[$section][$value]) ? $_G[$section][$value] : false;
		}

        if ($section) {
			return isset($_G[$section]) ? $_G[$section] : false;
		}

		return $_G;
	}
}

/* password_hash() 兼容PHP 5.0 以下版本 */

if (!defined('PASSWORD_BCRYPT')) {
    /**
     * PHPUnit Process isolation caches constants, but not function declarations.
     * So we need to check if the constants are defined separately from
     * the functions to enable supporting process isolation in userland
     * code.
     */
    define('PASSWORD_BCRYPT', 1);
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
    define('PASSWORD_BCRYPT_DEFAULT_COST', 10);
}

if (!function_exists('password_hash')) {
    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @param int    $algo     The algorithm to use (Defined by PASSWORD_* constants)
     * @param array  $options  The options for the algorithm to use
     *
     * @return string|false The hashed password, or false on error.
     */
    function password_hash($password, $algo, array $options = array()) {
        if (!function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_hash to function", E_USER_WARNING);
            return null;
        }
        if (is_null($password) || is_int($password)) {
            $password = (string) $password;
        }
        if (!is_string($password)) {
            trigger_error("password_hash(): Password must be a string", E_USER_WARNING);
            return null;
        }
        if (!is_int($algo)) {
            trigger_error("password_hash() expects parameter 2 to be long, " . gettype($algo) . " given", E_USER_WARNING);
            return null;
        }
        $resultLength = 0;
        switch ($algo) {
            case PASSWORD_BCRYPT:
                $cost = PASSWORD_BCRYPT_DEFAULT_COST;
                if (isset($options['cost'])) {
                    $cost = (int) $options['cost'];
                    if ($cost < 4 || $cost > 31) {
                        trigger_error(sprintf("password_hash(): Invalid bcrypt cost parameter specified: %d", $cost), E_USER_WARNING);
                        return null;
                    }
                }
                // The length of salt to generate
                $raw_salt_len = 16;
                // The length required in the final serialization
                $required_salt_len = 22;
                $hash_format = sprintf("$2y$%02d$", $cost);
                // The expected length of the final crypt() output
                $resultLength = 60;
                break;
            default:
                trigger_error(sprintf("password_hash(): Unknown password hashing algorithm: %s", $algo), E_USER_WARNING);
                return null;
        }
        $salt_req_encoding = false;
        if (isset($options['salt'])) {
            switch (gettype($options['salt'])) {
                case 'NULL':
                case 'boolean':
                case 'integer':
                case 'double':
                case 'string':
                    $salt = (string) $options['salt'];
                    break;
                case 'object':
                    if (method_exists($options['salt'], '__tostring')) {
                        $salt = (string) $options['salt'];
                        break;
                    }
                case 'array':
                case 'resource':
                default:
                    trigger_error('password_hash(): Non-string salt parameter supplied', E_USER_WARNING);
                    return null;
            }
            if (__strlen($salt) < $required_salt_len) {
                trigger_error(sprintf("password_hash(): Provided salt is too short: %d expecting %d", __strlen($salt), $required_salt_len), E_USER_WARNING);
                return null;
            } elseif (0 == preg_match('#^[a-zA-Z0-9./]+$#D', $salt)) {
                $salt_req_encoding = true;
            }
        } else {
            $buffer = '';
            $buffer_valid = false;
            if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
                $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
                if ($buffer) {
                    $buffer_valid = true;
                }
            }
            if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
                $strong = false;
                $buffer = openssl_random_pseudo_bytes($raw_salt_len, $strong);
                if ($buffer && $strong) {
                    $buffer_valid = true;
                }
            }
            if (!$buffer_valid && @is_readable('/dev/urandom')) {
                $file = fopen('/dev/urandom', 'r');
                $read = 0;
                $local_buffer = '';
                while ($read < $raw_salt_len) {
                    $local_buffer .= fread($file, $raw_salt_len - $read);
                    $read = __strlen($local_buffer);
                }
                fclose($file);
                if ($read >= $raw_salt_len) {
                    $buffer_valid = true;
                }
                $buffer = str_pad($buffer, $raw_salt_len, "\0") ^ str_pad($local_buffer, $raw_salt_len, "\0");
            }
            if (!$buffer_valid || __strlen($buffer) < $raw_salt_len) {
                $buffer_length = __strlen($buffer);
                for ($i = 0; $i < $raw_salt_len; $i++) {
                    if ($i < $buffer_length) {
                        $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                    } else {
                        $buffer .= chr(mt_rand(0, 255));
                    }
                }
            }
            $salt = $buffer;
            $salt_req_encoding = true;
        }
        if ($salt_req_encoding) {
            // encode string with the Base64 variant used by crypt
            $base64_digits =
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
            $bcrypt64_digits =
                './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $base64_string = base64_encode($salt);
            $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
        }
        $salt = __substr($salt, 0, $required_salt_len);
        $hash = $hash_format . $salt;
        $ret = crypt($password, $hash);
        if (!is_string($ret) || __strlen($ret) != $resultLength) {
            return false;
        }
        return $ret;
    }
    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => PASSWORD_BCRYPT_DEFAULT_COST,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     *
     * @return array The array of information about the hash.
     */
    function password_get_info($hash) {
        $return = array(
            'algo' => 0,
            'algoName' => 'unknown',
            'options' => array(),
        );
        if (__substr($hash, 0, 4) == '$2y$' && __strlen($hash) == 60) {
            $return['algo'] = PASSWORD_BCRYPT;
            $return['algoName'] = 'bcrypt';
            list($cost) = sscanf($hash, "$2y$%d$");
            $return['options']['cost'] = $cost;
        }
        return $return;
    }
    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash    The hash to test
     * @param int    $algo    The algorithm used for new password hashes
     * @param array  $options The options array passed to password_hash
     *
     * @return boolean True if the password needs to be rehashed.
     */
    function password_needs_rehash($hash, $algo, array $options = array()) {
        $info = password_get_info($hash);
        if ($info['algo'] !== (int) $algo) {
            return true;
        }
        switch ($algo) {
            case PASSWORD_BCRYPT:
                $cost = isset($options['cost']) ? (int) $options['cost'] : PASSWORD_BCRYPT_DEFAULT_COST;
                if ($cost !== $info['options']['cost']) {
                    return true;
                }
                break;
        }
        return false;
    }
    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash     The hash to verify against
     *
     * @return boolean If the password matches the hash
     */
    function password_verify($password, $hash) {
        if (!function_exists('crypt')) {
            trigger_error("Crypt must be loaded for password_verify to function", E_USER_WARNING);
            return false;
        }
        $ret = crypt($password, $hash);
        if (!is_string($ret) || __strlen($ret) != __strlen($hash) || __strlen($ret) <= 13) {
            return false;
        }
        $status = 0;
        for ($i = 0; $i < __strlen($ret); $i++) {
            $status |= (ord($ret[$i]) ^ ord($hash[$i]));
        }
        return $status === 0;
    }
}

if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}

/**
 *  针对PHP内置函数的优化
 *
 *  前面加上横杠，防止与PHP默认函数名冲突
*/

/**
 * Count the number of bytes in a string
 *
 * We cannot simply use strlen() for this, because it might be overwritten by the mbstring extension.
 * In this case, strlen() will count the number of *characters* based on the internal encoding. A
 * sequence of bytes might be regarded as a single multibyte character.
 *
 * @param string $binary_string The input string
 *
 * @internal
 * @return int The number of bytes
 */
function __strlen($binary_string) {
    if (function_exists('mb_strlen')) {
        return mb_strlen($binary_string, '8bit');
    }
    return strlen($binary_string);
}
/**
 * Get a substring based on byte limits
 *
 * @see _strlen()
 *
 * @param string $binary_string The input string
 * @param int    $start
 * @param int    $length
 *
 * @internal
 * @return string The substring
 */
function __substr($binary_string, $start, $length) {
    if (function_exists('mb_substr')) {
        return mb_substr($binary_string, $start, $length, '8bit');
    }
    return substr($binary_string, $start, $length);
}
/*
 * 设置指定的Cookie值
 *
 * @param integer $expire 会话过期时间
*/
function __setcookie($key, $value, $expire = 0, $path = null){
    if(is_null($path)) $path = G('system','path');
    // 空格转%20s
    setrawcookie($key, rawurlencode($value), $expire, $path);
    $_COOKIE[$key] = $value;
}
// 获取指定的Cookie值
function __getcookie($key, $default = null, $path = null){
    if(is_null($path)) $path = G('system','path');

    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
}
// 删除指定的Cookie
function __unsetcookie($key, $path = null){
    if(is_null($path)) $path = G('system','path');

    if (!isset($_COOKIE[$key])) {
        return;
    }
    setcookie($key, '', time() - 2592000, $path);
    unset($_COOKIE[$key]);
}
// 添加或获取指定的session值
function __session($key, $value = ''){
    if( !isset( $key ) ) return $_SESSION;

    if(empty($value)){
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }
    $_SESSION[$key] =  $value;
    return $value;
}
// 删除session
function __unsetsession($key){
    if(isset($_SESSION[$key])){
        unset($_SESSION[$key]);
    }
}

// 打印出漂亮的数组 $debuger 为程序断点
function __print( $array, $debuger = false ){
    if( G('system','debug') ){
        echo '<pre>',print_r((array) $array),'</pre>';
        if($debuger) die('------- STOP -------');
    }
}

// 获取IP地址
function getIp(){
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
// 获取位置（新浪接口）
function getCity(){
    $json = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.getIp());
    if(isset($json)){
        $address = json_decode($json,true);
        if($address !== -3){
            return $address['province'].$address['city'];
        }
    }
    return '火星';
}
/**
 * 移动上传文件
 *
 * @param   string  $from   文件来源
 * @param   string  $target 移动目标地
 * @return  boolean
 */
function uploadMove( $from, $target= '' ){

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
// 删除文件夹
function removeDir( $dir ){

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
// 格式化大小
function formatSize($filesize){
    $unit = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($f = 0; $filesize >= 1024 && $f < 4; $f++){
        $filesize /= 1024;
    }
    return round($filesize, 2).$unit[$f];
}
/**
 * 返回转义后的HTML内容
 *
 * @param   mixed   $var 需要转义的字符串的输入字符串或数组
 * @param   bool    $doubleEncode   $doubleEncode 设置为false防止转义两次
 * @return  mixed 返回转义后的字符串或数组的结果
 */
function htmlEscape($var, $doubleEncode = true) {

    if (empty($var)) {
        return $var;
    }

    if (is_array($var)) {
        foreach (array_keys($var) as $key) {
            $var[$key] = html_escape($var[$key], $doubleEncode);
        }
        return $var;
    }

    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $doubleEncode);
}