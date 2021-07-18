<?php
$route->group('/notification', function ($route) {
    $route->get('/read', function () {
        $this->view('notification');
    });
    
    $route->get('/unread', function () {
        $this->view('notification');
    });
    
    $route->get('/delete', function () {
    });
    
    $route->get('/delete-batch', function () {
    });
});