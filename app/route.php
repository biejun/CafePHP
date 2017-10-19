<?php

$route->get('/',function(){
	//$this->view->tpl('index');
	$demo = $this->import('admin@api');
	$post = $this->import('post@api');
	$post->hello();
	//$this->response->json(array('dd'));
});