<?php
/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

	$route->get('/index',function(){
		//$this->action->on('check:login');
		$this->view('index');
	});

    /* 路径为 /admin/login */
    $route->get('/login',function(){
        $csrf = strtoupper( md5( uniqid(rand(), true) ) );
        $this->session->set('login_csrf',$csrf);
        $this->view->assign('__csrf__',$csrf);
        $this->view('login');
    });

    /* 退出登录 */
    $route->get('/logout',function(){
        $this->session->destroy();
        $this->cookie->delete('user_login_token');
        $this->response->location('login');
    });

    /* 控制台页面 */
    $route->group('/console',function($route){

        /* 路径为 /admin/console/backup */
        $route->get('/backup',function(){
            $this->action->on('check:login');
            $this->view->assign('data',$this->load('admin')->getBackupFiles());
            $this->view('console-backup');
        });

        $route->post('/add/todo',function(){
            $data = $this->request->post();
            $uid = $this->session->get('login_uid');
            $text = filter_var($data['text'],FILTER_UNSAFE_RAW);
            $level = filter_var($data['level'],FILTER_VALIDATE_INT);
            $this->load('admin@todolists')->add($uid, $text, $level);
            $this->response->json('创建成功！');
        });

        $route->post('/backup/export',function(){
            $this->action->on('check:login');
            $username = $this->session->get('login_name');
            if($this->load('admin')->exportBackup()){
                $this->load('admin@logs')->addOperateLog($username,'备份了数据库');
                $this->action->on('admin:notify','success','备份成功!');
            }else{
                $this->action->on('admin:notify','error','备份失败!');
            }
            $this->response->goBack();
        });

        $route->post('/backup/restore',function(){
            $this->action->on('check:login');
            $username = $this->session->get('login_name');
            $file = trim($this->request->post('file'));
            if($this->load('admin')->restoreBackup($file)){
                $this->load('admin@logs')->addOperateLog($username,"还原了数据库备份文件：{$file}");
                $this->action->on('admin:notify','success','还原成功!');
            }else{
                $this->action->on('admin:notify','error','还原失败!');
            }
            $this->response->goBack();
        });

        $route->post('/backup/delete',function(){
            $this->action->on('check:login');
            $username = $this->session->get('login_name');
            $file = trim($this->request->post('file'));
            if($this->load('admin')->deleteBackup($file)){
                $this->load('admin@logs')->addOperateLog($username,"删除了数据库备份文件：{$file}");
                $this->action->on('admin:notify','success','删除成功!');
            }else{
                $this->action->on('admin:notify','error','删除失败!');
            }
            $this->response->goBack();
        });

    });

    /* 系统设置 */
    $route->group('/options',function($route){

        $route->get('/config',function(){
            $this->action->on('check:login');
            $this->view('options-config');
        });

        $route->post('/update',function(){
            $data = json_decode($this->request->post('data'));
            try{
                $this->load('admin@options')->updateAll($data);
                $this->action->on('admin:notify','success','更新成功！!');
                $this->response->json('更新成功！');

            }catch(\Exception $e){
                $this->action->on('admin:notify','error','更新失败！!');
                $this->response->json($e->getMessage(),false);
            }
        });
    });

    /* 给账号操作分一个路径组 规则: /admin/account/ */
    $route->group('/account',function($route){

        /* 登录账号 */
        $route->post('/login',function(){

            $data = $this->request->post();

            $username = filter_var($data['username'],FILTER_SANITIZE_STRING);

            $password = filter_var($data['password'],FILTER_UNSAFE_RAW);

            $csrf = filter_var($data['__csrf__'],FILTER_UNSAFE_RAW);

            if(is_null($this->session->get('login_csrf')) || $csrf != $this->session->get('login_csrf')){
                $this->response->json('请求参数错误!',false);
            }
            if(empty($username)||!$this->load('admin@users')->checkUsername($username)){
                $this->response->json('用户不存在!',false);
            }
            if(empty($password)||!isset($password{5})){
                $this->response->json('密码不能少于六位!',false);
            }
            if($this->load('admin@users')->checkPassword($username, $password)){
                $tokens = $this->load('admin@users')->updateToken($username);
                $this->cookie->set('user_login_token', $tokens['token'], strtotime($tokens['timeout']));
                // 从会话中删除已验证过得CSRF令牌
                $this->session->delete('login_csrf');
                // 登录日志
                $this->load('admin@logs')->addLoginLog($username);
                $this->response->json('登录成功!');
            }else{
                $this->response->json('用户名与密码不匹配！', false);
            }
        });

        /* 添加账号 */
        $route->post('/add',function(){

        });

        /* 删除账号 */
        $route->post('/delete',function(){

        });

        $route->get('/operate',function(){
            $this->action->on('check:login');
            $this->view('account-operate');
        });

        $route->get('/profile',function(){
            $this->action->on('check:login');
            $this->view('account-profile');
        });
    });

    /* 程序安装 */
    $route->post('/step-one-'.HASH,function(){

        $data = $this->request->post();

        $db = "<?php if(!defined(ABSPATH)) die();\n return ".var_export($data,true).";";

        if(file_put_contents('Config/config.db.php',$db,LOCK_EX)){
            $this->response->json('Successful!');
        }else{
            $this->response->json('Unable to open file!',false);
        }
    });

    $route->post('/setup-two-'.HASH,function(){
        die('1212');
        // extract($this->request->post());

        // if(empty($username) || empty($password) || empty($passwordonce))
        //     $this->response->json('请填写用户名和密码！',false);
        // if(!isset($password{5})) $this->response->json('密码不能少于6位！',false);
        // if(strcmp($password,$passwordonce) == 0){
        //     $user = array();
        //     $user['name'] = trim($username);
        //     $user['password'] = trim($password);
        //     $user['level'] = '10';
        //     $user['is_admin'] = 'true';

        //     if(!empty($safetycode)) $user['safetycode'] = $safetycode;

        //     try{
        //         $this->load('admin@install')->import();
        //         $this->load('admin@users')->add($user);
        //     }catch(Exception $e){
        //         $this->response->json($e->getMessage(),false);
        //     }
        //     $this->load('admin@install')->lock();
        //     $this->response->json('创建成功！');
        // }else{
        //     $this->response->json('两次输入的密码不一致！',false);
        // }
    });

    /**
     * 定义一个公用API接口
     * 这条路由规则将会自动匹配components目录下的Api.php文件
     */
    $route->post('/api/:func',function($func){

        $this->action->on('check:login');

        $args = $this->request->post();

        if(!empty($func)){
            $data = $this->load('admin@api')->run($func,$args);
            if($data){
                $this->response->json($data);
            }else{
                $this->response->json('参数错误',false);
            }
        }
    });
});