<?php

$realPath = realpath(__DIR__ . '/..');

$scriptName = ltrim($_SERVER['SCRIPT_NAME'],'/');

require __DIR__.'/Loader.php';

\Coffee\Loader::register($realPath);

// 安装程序与程序入口文件分离
($scriptName === 'index.php') && (new \Coffee\Foundation\App)->setRoot($realPath)->start();