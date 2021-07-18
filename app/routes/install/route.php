<?php 
$route->group('/install', function($route) {
    
    $route->get('/index', function() {
        $data = [
          'token' => TOKEN,
          'checkVersion' => version_compare(PHP_VERSION, '7.1.0', '>')  
        ];
        $this->render('index', $data);
    });
    
    $route->post('/setup-one', function($req, $res) {
        if(!isset($_SERVER['HTTP_TOKEN']) || !IS_DEVELOPMENT) $res->json('请求非法!');
        $data = [];
        $data['driver'] = 'mysql';
        $data['host'] = $req->post('host');
        $data['username'] = $req->post('username');
        $data['password'] = $req->post('password');
        $data['database'] = $req->post('database');
        $data['port'] = (int) $req->post('port');
        $data['charset'] = $req->post('charset');
        $data['collation'] = $req->post('charset')."_unicode_ci";
        $data['prefix'] = '';
        $db = "<?php\n return ".var_export($data,true).";";
        if(!is_writable($this->app->configPath())) {
            $res->json('config 目录不可写!',false);
        }
        $file = $this->app->configPath('config.db.php');
        if(!file_put_contents($file, $db, LOCK_EX)){
            $res->json('数据库配置文件创建失败!',false);
        }
        try{
            $this->model->load('Install')->importData($data);
        }catch(Exception $e){
            $res->json($e->getMessage(), false);
        }
        $res->json('数据库配置成功');
    });
    
    $route->post('/setup-two', function($req, $res) {
        if(!isset($_SERVER['HTTP_TOKEN']) || !IS_DEVELOPMENT) $req->json('请求非法!');
        $username = trim($req->post('username'));
        $password = trim($req->post('password'));
        $passwordonce = trim($req->post('passwordonce'));
        if(empty($username) || empty($password) || empty($passwordonce)) {
            $res->json('请填写用户名和密码！',false);
        }
        if(!isset($password{5})) $res->json('密码不能少于6位！',false);
        if(strcmp($password,$passwordonce) == 0){
            $user = array();
            $user['name'] = $username;
            $user['email'] = $email;
            $user['nickname'] = $username;
            $user['password'] = $password;
            $user['level'] = '777';
            $user['is_admin'] = 'true';

            if(!defined('HASH')){
                $file = fopen($this->app->configPath('constants.php'), 'ab');
                fwrite($file, "\n/* 数据加密密钥 (Non modifiable) */\ndefine( 'HASH', '{$hash}' );");
                fclose($file);
            }
            $safetycode = trim($req->post('safetycode'));
            if(!empty($safetycode)) $user['safetycode'] = $safetycode;
            try{
                if ($this->model->load('Users')->add($user)) {
                    $this->model->load('Install')->lock();
                    $res->json('账号注册成功！');
                }else{
                    $res->json('账号注册失败！');
                }
            }catch(Exception $e){
                $res->json($e->getMessage(),false);
            }
        }else{
            $res->json('两次输入的密码不一致！',false);
        }
    });
});