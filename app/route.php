<?php

$route->get('/',function(){

    $this->view($this->existLock()?'index':'install');
});