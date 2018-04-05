<?php

$route->get('/',function(){
    $this->action->on('check:install');
    $t = $this->load('admin@users')->getData();
    print_r($t);
    $this->view('index');
});

$route->get('/test',function(){
    $todo = ['name'=>'biejun','text'=>'ghhh'];
    $data = [['name'=>'biejun','text'=>'ghhh'],['name'=>'xiaotian','text'=>'nidamm',['name'=>'biejun','text'=>'ttttt']]];

        foreach ($data as &$value) {
            if($value['text'] == $todo['text']) {
                echo 'ddd';
                $value['text'] = 'wwwwww';
                break;
            }
        }
    print_r($data);
    //$this->load('admin@users')->add(['name'=>'xiaotian1992','password'=>'123456']);
});