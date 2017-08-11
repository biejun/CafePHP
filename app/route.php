<?php if(!isset($route)) exit;

$route->get('/',function($req,$res){
	$res->view->show('index');
});

$route->get('/login',function($req,$res){
	$res->view->show('index');
});