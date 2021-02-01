<?php
/* $this->grounp() 为相同前缀路径分组*/
$route->group('/welcome',function($route){

    $route->get('/index',function(){
        $this->action->on('check:login');
        $this->view('index');
    });

    $route->get('/profile',function(){
        $this->action->on('check:login');
        $uid = $this->session->get('login_uid');
        $this->view->assign('data', $this->load('welcome@users')->getMeta($uid));
        $this->view('profile');
    });
	
	$route->get('/users',function(){
	    $this->action->on('check:login');
	    $this->view('users');
	});
	
	/* 系统设置 */
	$route->group('/console',function($route){
		
	    $route->get('/config',function(){
	        $this->action->on('check:login');
	        $this->view('config');
	    });
		
		$route->post('/config/options',function(){
		    $this->action->on('check:login');
			$data = $this->load('welcome@options')->getAllAndExtra();
		    $this->response->json($data);
		});
		
		$route->post('/config/update',function(){
		    $data = json_decode($this->request->post('data'));
		    try{
		        $this->load('welcome@options')->update($data);
		        $this->response->json('更新成功！');
		    }catch(\Exception $e){
		        $this->response->json($e->getMessage(),false);
		    }
		});
		
		$route->get('/backup',function(){
		    $this->action->on('check:login');
		    $this->view->assign('data',$this->load('welcome@admin')->getBackupFiles());
		    $this->view('backup');
		});
		
        $route->post('/backup/export',function(){
            $this->action->on('check:login');
            $username = $this->session->get('login_name');
            if($this->load('welcome@admin')->exportBackup()){
                $this->load('welcome@logs')->add('operate', $username, '进行了数据备份');
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
            if($this->load('welcome@admin')->restoreBackup($file)){
                $this->load('welcome@logs')->add('operate', $username, "数据库备份文件“{$file}”已还原");
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
            if($this->load('welcome@admin')->deleteBackup($file)){
                $this->load('welcome@logs')->add('operate',$username,"数据库备份文件“{$file}”已删除");
                $this->action->on('admin:notify','success','删除成功!');
            }else{
                $this->action->on('admin:notify','error','删除失败!');
            }
            $this->response->goBack();
        });
	});
	
    /* todo list */
    $route->group('/todo',function($route) {

        $route->post('/add',function(){
            $data = $this->request->post();
            $uid = $this->session->get('login_uid');
            $text = filter_var($data['text'],FILTER_UNSAFE_RAW);
            $level = filter_var($data['level'],FILTER_VALIDATE_INT);
            $todo = $this->load('welcome@todolists')->add($uid, $text, $level);
            $this->response->json($todo);
        });

        $route->post('/done',function(){
            $data = $this->request->post();
            $uid = $this->session->get('login_uid');
            $time = filter_var($data['time'],FILTER_UNSAFE_RAW);
            $this->load('welcome@todolists')->complete($uid, $time);
            $this->response->json('任务状态更新成功！');
        });

        $route->post('/remove',function(){
            $data = $this->request->post();
            $uid = $this->session->get('login_uid');
            $todos = filter_var($data['todos'],FILTER_UNSAFE_RAW);
            $this->load('welcome@todolists')->delete($uid, $todos);
            $this->response->json('任务移除成功！');
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
            $data['port'] = (int) $port;
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
                    $this->load('welcome@install')->import();
                    $this->load('welcome@users')->add($user);
                }catch(Exception $e){
                    $this->response->json($e->getMessage(),false);
                }
                $this->load('welcome@install')->lock();
                $this->response->json('创建成功！');
            }else{
                $this->response->json('两次输入的密码不一致！',false);
            }
        }
    });

    $route->group('/api',function($route){

        /* 登录账号 */
        $route->post('/login',function(){
            if(!isset($_SERVER['HTTP_TOKEN'])) {
				$this->response->json('无效令牌，登录失败！', false);
			}
            $data = $this->request->post();
            $username = filter_var($data['username'],FILTER_SANITIZE_STRING);
            $password = filter_var($data['password'],FILTER_UNSAFE_RAW);
            $csrf = filter_var($data['__csrf__'],FILTER_UNSAFE_RAW);
            $captcha = filter_var($data['captcha'],FILTER_SANITIZE_STRING);
            $keepStatus = filter_var($data['keepStatus'], FILTER_VALIDATE_BOOLEAN);
            if( is_null($this->session->get('login_csrf')) 
			  || $csrf != $this->session->get('login_csrf')){
                $this->response->json('请求参数错误!',false);
            }
            if (empty($username) 
			  || !$this->load('welcome@users')->checkUsername($username)){
                $this->response->json('用户不存在!',false);
            }
            if( empty($password) ){
                $this->response->json('密码不能为空!',false);
            }
            $checkCaptcha = $this->captcha->check($captcha, 'login');
            if( !$checkCaptcha ) {
                $this->response->json('验证码不正确!',false);
            }
            if($this->load('welcome@users')->checkPassword($username, $password)){
                $tokens = $this->load('welcome@users')->updateToken($username, $keepStatus ? 180 : 1);
                $this->cookie->set('user_login_token', $tokens['token'], strtotime($tokens['timeout']));
                // 从会话中删除已验证过得CSRF令牌
                $this->session->delete('login_csrf');
                // 登录日志
                $this->load('welcome@logs')->add('logged', $username, getCity());
                $this->response->json('登录成功!');
            }else{
                $this->response->json('用户名与密码不匹配！', false);
            }
        });

        /**
         * 定义一个公用API接口
         * 这条路由规则将会自动匹配components目录下的Api.php文件
         */
        $route->post('/query/:func',function($func) {
            $this->action->on('check:login');
            $args = $this->request->post();
            if(!empty($func)){
                try {
                    $data = $this->load('welcome@api')->run($func,$args);
                    $this->response->json($data);
                } catch (Exception $e) {
                    $this->response->json('参数错误',false);
                }
            }
        });
    });
});