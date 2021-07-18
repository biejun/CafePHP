<?php
$route->group('/user', function ($route) {
    
    $route->get('/at-search', function ($req, $res) {
        $name = $req->get('name');
        if ($name) {
            $data = model('Users')->search($name);
            foreach ($data as $row) {
                $row->avatar = u($row->avatar);
                $row->url = u($row->name);
            }
            $res->json($data);
        } else {
            $res->json([]);
        }
    });
    
    /* 登录账号 */
    $route->post('/login', function ($req, $res) {
        if (!isset($_SERVER['HTTP_TOKEN'])) {
            $res->json('令牌无效，请刷新页面重试！', false);
        }
        $username = $req->post('username');
        $password = $req->post('password', FILTER_UNSAFE_RAW);
        $csrf = $req->post('__csrf__');
        $captcha = $req->post('captcha');
        $keepStatus = $req->post('captcha', FILTER_VALIDATE_BOOLEAN);
        
        $sessionCSRF = app('session')->get('login_csrf');
        
        if (is_null($sessionCSRF) || !hash_equals($csrf, $sessionCSRF)) {
            $res->json('请求参数错误!', false);
        }
        if (empty($username)) {
            $res->json('请输入用户名!', false);
        }
        if (!preg_match('/^[A-Za-z0-9_]{4,}$/u', $username)) {
            $res->json('用户名仅支持字母、数字及下划线，且不少于4位', false);
        }
        
        $users = model('Users');
        
        if (!$users->checkUserName($username)) {
            $res->json('用户名不存在!', false);
        }
        if (empty($password)) {
            $res->json('请输入密码!', false);
        }
        if (!app('captcha')->check($captcha, 'login')) {
            $res->json('验证码不正确!', false);
        }
        if ($users->checkPassword($username, $password)) {
            $days = $keepStatus ? 180 : 1;
            $info = $users->updateToken($username, $days);
            
            $account = model('Account');
            $account->setToken($info['token'], strtotime($info['timeout']));
            
            if ($account->recordInfo($info['token'])) {
                // 从会话中删除已验证过得CSRF令牌
                app('session')->delete('login_csrf');
                // 登录日志
                //$this->load('welcome@logs')->add('logged', $username, getCity());
                $res->json('登录成功!');
            } else {
                $res->json('登录失败!');
            }
        } else {
            $res->json('密码不正确，请重新输入！', false);
        }
    });
    
    $route->post('/register', function ($req, $res) {
        if (!isset($_SERVER['HTTP_TOKEN'])) {
            $res->json('令牌无效，请刷新页面重试！', false);
        }
        $username = $req->post('username');
        $password = $req->post('password', FILTER_UNSAFE_RAW);
        $csrf = $req->post('__csrf__');
        $captcha = $req->post('captcha');
        
        $sessionCSRF = app('session')->get('register_csrf');
        
        if (is_null($sessionCSRF) || !hash_equals($csrf, $sessionCSRF)) {
            $res->json('请求参数错误!', false);
        }
        if (empty($username)) {
            $res->json('请输入用户名!', false);
        }
        if (!preg_match('/^[A-Za-z0-9_]{4,}$/u', $username)) {
            $res->json('用户名仅支持字母、数字及下划线，且不少于4位', false);
        }
        
        $users = model('Users');
        $account = model('Account');
        
        if ($users->checkUserName($username) || $account->isInvalidName($username)) {
            $res->json('用户名已被占用，换其他名字试试!', false);
        }
        if (empty($password)) {
            $res->json('请输入密码!', false);
        }
        
        if (!app('captcha')->check($captcha, 'register')) {
            $res->json('验证码不正确!', false);
        }
        
        $user = [];
        $user['name'] = $username;
        $user['nickname'] = $username;
        $user['password'] = $password;
        
        if ($users->add($user)) {
            // 失效时间（天）
            $days = 1;
            // 记录token
            $info = $users->updateToken($username, $days);
            // 记录cookie
            $account->setToken($info['token'], strtotime($info['timeout']));
            
            if ($account->recordInfo($info['token'])) {
                // 从会话中删除已验证过得CSRF令牌
                app('session')->delete('register_csrf');
                $res->json('注册成功！');
            } else {
                $res->json('注册失败！');
            }
        } else {
            $res->json('注册失败！', false);
        }
    });
    
    $route->get('/check-name/:name', function ($req, $res) {
        $name = $req->param('name');
        $exsit = model('Users')->checkUserName($name);
        $res->json($exsit ? '用户名已被占用，换其他名字试试!' : '', !$exsit);
    });
});
