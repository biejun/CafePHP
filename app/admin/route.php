<?php
/* 依赖类库 */
use Coffee\Support\Helper;

/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

	/* 路径为 /admin/login */
	$route->get('/login',function(){
		$this->render('login');
	});

	$route->get('/install',function(){
		echo 'Install Project!';
	});

	$route->group('/console',function($route){

		/* 路径为 /admin/console/index */
		$route->get('/:id',function($id){

			$res = Helper::validate($id,'integer');

			echo $res;
		});
	});
});