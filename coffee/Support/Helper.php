<?php
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

/**
 * 系统应用开发助手
 *
 * @package Coffee\Foundation\Helper
 * @since 0.0.5 帮助你开发更便捷
 */

namespace Coffee\Support;

class Helper
{
	/**
	 * 检查用户输入
	 *
	 * @param string $value 输入值
	 * @param string $rule 默认类型或正则表达式
	 * @return void
	 */
	public static function validate($value,$rule)
	{

		$validate = [
			'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
			'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
			'currency'  =>  '/^\d+(\.\d+)?$/',
			'integer'   =>  '/^[-\+]?\d+$/',
			'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
			'english'   =>  '/^[A-Za-z]+$/',
			'chinese'	=>	'/^([\xE4-\xE9][\x80-\xBF][\x80-\xBF])+$/',
			'username'	=>	'/^[A-Za-z0-9_]+$/',
			'nickname'	=>	'/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9、。&]+$/u',
			'phone'		=>	'/^1[34578]{1}\d{9}$/',
			'qq'		=>	'/^[1-9]\d{4,12}$/',
		];

		if(isset($validate[$rule])) $rule = $validate[$rule];

		return preg_match( $rule,trim($value) );
	}
}