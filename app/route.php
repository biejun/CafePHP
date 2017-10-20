<?php

$route->get('/',function(){

	$demo = $this->import('admin@api');
	$post = $this->import('post@api');
	$post->hello();
});

/* 安装系统 */
$route->get('/install',function(){

	$this->view->setView('install');
	$this->render('index');
});