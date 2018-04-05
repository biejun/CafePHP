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

/* 载入系统模块加载器 */
require __DIR__  . '/Loader.php';

/* 注册系统模块加载器 */
\Coffee\Loader::register();

(new \Coffee\Foundation\Container)->run();