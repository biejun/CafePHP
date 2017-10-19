<?php

/* 安装系统 */
$route->get('/install/system',function(){
	$step = $this->request->get('step');

	if($step == 1){

	}

	echo $step;
});