<?php
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @link 	 https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2021 Jun Bie
 * @license This content is released under the MIT License.
 */

/* 载入系统常量 */
require __DIR__ .'/../config/constants.php';
/* 载入核心 */
require __DIR__ .'/../vendor/autoload.php';

$app = new Cafe\Foundation\App(dirname(__DIR__));
$app->bind(Cafe\Http\Server::class);
$app->make(Cafe\Http\Server::class)->run(); 
