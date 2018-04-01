<?php

$route->get('/',function(){

	$this->view->assign('suffixVersion',date('ymdHi'));

	$this->response->render($this->checkSystemInstall()?'index':'install');
});