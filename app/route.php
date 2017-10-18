<?php

$route->get('/',function(){
	//$this->view->tpl('index');
	$this->response->json(array('dd'));
});