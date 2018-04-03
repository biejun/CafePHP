<?php

$route->get('/',function(){
    $this->action->on('check:install');
    $this->view('index');
});