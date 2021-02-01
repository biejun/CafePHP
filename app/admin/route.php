<?php
/* $this->grounp() 为相同前缀路径分组*/
$route->group('/admin',function($route){

    $route->get('/index',function(){
        $this->action->on('check:login');
        $this->view('index');
    });

    $route->get('/profile',function(){
        $this->action->on('check:login');
        $uid = $this->session->get('login_uid');
        $this->view->assign('data', $this->load('admin@users')->getMeta($uid));
        $this->view('account-profile');
    });

    $route->get('/backup',function(){
        $this->action->on('check:login');
        $this->view->assign('data',$this->load('admin')->getBackupFiles());
        $this->view('console-backup');
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
                $this->load('admin@options')->update($data);
                $this->action->on('admin:notify','success','更新成功！!');
                $this->response->json('更新成功！');

            }catch(\Exception $e){
                $this->action->on('admin:notify','error','更新失败！!');
                $this->response->json($e->getMessage(),false);
            }
        });
    });

    $route->group('/api',function($route){

        $route->post('/backup/export',function(){
            $this->action->on('check:login');
            $username = $this->session->get('login_name');
            if($this->load('admin')->exportBackup()){
                $this->load('admin@logs')->add('operate', $username, '进行了数据备份');
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
                $this->load('admin@logs')->add('operate', $username, "数据库备份文件“{$file}”已还原");
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
                $this->load('admin@logs')->add('operate',$username,"数据库备份文件“{$file}”已删除");
                $this->action->on('admin:notify','success','删除成功!');
            }else{
                $this->action->on('admin:notify','error','删除失败!');
            }
            $this->response->goBack();
        });

        $route->post('/config/update',function(){
            $data = json_decode($this->request->post('data'));
            try{
                $this->load('admin@options')->update($data);
                $this->response->json('更新成功！');
            }catch(\Exception $e){
                $this->response->json($e->getMessage(),false);
            }
        });

        $route->post('/logged/delete',function(){
            $this->action->on('check:login');
            if($this->load('admin@logs')->delete('logged')){
                $this->response->json('清空成功!', true);
            }else{
                $this->response->json('清空失败!', false);
            }
        });

        $route->post('/operate/delete',function(){
            $this->action->on('check:login');
            if($this->load('admin@logs')->delete('operate')){
                $this->response->json('清空成功!', true);
            }else{
                $this->response->json('清空失败!', false);
            }
        });
    });

    /* 程序安装 */
    $route->post('/setup-one',function(){
        if(isset($_SERVER['HTTP_TOKEN']) && IS_DEVELOPMENT) {
            extract($this->request->post());

            $data = [];
            $data['host'] = $host;
            $data['user'] = $user;
            $data['password'] = $password;
            $data['name'] = $name;
            $data['port'] = null;
            $data['prefix'] = $prefix;
            $data['charset'] = $charset;

            $db = "<?php\n return ".var_export($data,true).";";

            $file = CONFIG.'/config.db.php';
            if(file_put_contents($file,$db,LOCK_EX)){
                $this->response->json('创建数据库配置文件成功!');
            }else{
                $this->response->json('创建数据库配置文件失败!',false);
            }
        }
    });

    $route->post('/setup-two',function() {
        if(isset($_SERVER['HTTP_TOKEN']) && IS_DEVELOPMENT) {
            extract($this->request->post());

            if(empty($username) || empty($password) || empty($passwordonce)) {
                $this->response->json('请填写用户名和密码！',false);
            }
            if(!isset($password{5})) $this->response->json('密码不能少于6位！',false);
            if(strcmp($password,$passwordonce) == 0){
                $user = array();
                $user['name'] = trim($username);
                $user['password'] = trim($password);
                $user['level'] = '-10';
                $user['is_admin'] = 'true';

                if(!defined('HASH')){
                    $file = fopen(CONFIG.'/constants.php', 'ab');
                    fwrite($file, "\n/* 数据加密密钥 (Non modifiable) */\ndefine( 'HASH', '{$hash}' );");
                    fclose($file);
                }
                
                if(!empty($safetycode)) $user['safetycode'] = $safetycode;
                try{
                    $this->load('admin@install')->import();
                    $this->load('admin@users')->add($user);
                }catch(Exception $e){
                    $this->response->json($e->getMessage(),false);
                }
                $this->load('admin@install')->lock();
                $this->response->json('创建成功！');
            }else{
                $this->response->json('两次输入的密码不一致！',false);
            }
        }
    });
});