<?php

$route->get('/',function(){

	$this->view->assign('suffixVersion',date('ymdHi'));

	$this->render($this->checkSystemInstall()?'index':'install');
});