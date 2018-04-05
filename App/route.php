<?php
/* 首页 */
$route->get('/',function(){
    $this->action->on('check:install');
    $this->view('index');
});

$route->get('/test',function(){

    //$this->load('admin@users')->add(['name'=>'xiaotian1992','password'=>'123456']);
});