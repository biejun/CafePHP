<?php
/* 首页 */
$route->get('/', function () {
    $this->action->on('check:install');
    $this->view('index');
});
/* 登录 */
$route->get('/login', function () {
    $csrf = strtoupper(md5(uniqid(rand(), true)));
    $this->session->set('login_csrf', $csrf);
    $this->view->assign('token', TOKEN);
    $this->view->assign('__csrf__', $csrf);
    $this->view->assign('title', '登录');
    $this->view('login');
});
/* 注册 */
$route->get('/register', function () {
    $csrf = strtoupper(md5(uniqid(rand(), true)));
    $this->session->set('login_csrf', $csrf);
    $this->view->assign('token', TOKEN);
    $this->view->assign('__csrf__', $csrf);
    $this->view->assign('title', '注册');
    $this->view('/login');
});

/* 退出登录 */
$route->get('/logout', function () {
    $this->action->on('check:login');
    $this->session->destroy();
    $this->cookie->delete('user_login_token');
    $this->response->location('login');
});

$route->get('/captcha/:token/:id', function ($token, $id) {
    if (isset($token) && isset($id)) {
        $this->captchaImage($id);
    }
});
