<?php

$route->get('/',function(){

	$this->response->render($this->existLock()?'index':'index');
});