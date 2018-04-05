<?php

$route->get('/',function(){
    $this->action->on('check:install');
    
    $this->view('index');
});

$route->get('/test',function(){
    echo sprintf("%1 nimei %1 hahah %2",'b','woll');
});