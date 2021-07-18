<?php 
$route->group('/admin', function($route) {
    
    $route->on('auth:permissions')->get('/index', function() {
        $this->render('index');
    });
    
    /* 系统设置 */
    $route->on('auth:permissions')->group('/console',function($route){
    	
    	$route->get('/users',function(){
    	    $this->render('users');
    	});
    	
        $route->get('/config',function() {
            $this->render('config');
        });
    	
    	$route->post('/config/options',function($req, $res){
    		$data = model('AdminOptions')->getAllAndExtra();
    	    $res->json($data);
    	});
    	
    	$route->post('/config/update',function($req, $res){
    	    $data = json_decode($req->post('data', FILTER_UNSAFE_RAW));
    	    try{
    	        model('AdminOptions')->update($data);
    	        $res->json('更新成功！');
    	    }catch(\Exception $e){
    	        $res->json($e->getMessage(),false);
    	    }
    	});
    	
    	$route->get('/backup',function(){
            $data = [
                'data' => model('Admin')->getBackupFiles()
            ];
    	    $this->render('backup', $data);
    	});
    	
        $route->post('/backup/export',function($req, $res){
            $username = model('Account')->name();
            if(model('Admin')->exportBackup()){
                model('AdminLog')->add('operate', $username, '进行了数据备份');
                //$this->action->on('admin:notify','success','备份成功!');
            }else{
                //$this->action->on('admin:notify','error','备份失败!');
            }
            $res->goBack();
        });
    
        $route->post('/backup/restore',function($req, $res){
            $username = model('Account')->name();
            $file = trim($req->post('file'));
            if(model('Admin')->restoreBackup($file)){
                model('AdminLog')->add('operate', $username, "数据库备份文件“{$file}”已还原");
                //$this->action->on('admin:notify','success','还原成功!');
            }else{
                //$this->action->on('admin:notify','error','还原失败!');
            }
            $res->goBack();
        });
    
        $route->post('/backup/delete',function($req, $res){
            $username = model('Account')->name();
            $file = trim($req->post('file'));
            if(model('Admin')->deleteBackup($file)){
                model('AdminLog')->add('operate',$username,"数据库备份文件“{$file}”已删除");
                //$this->action->on('admin:notify','success','删除成功!');
            }else{
                //$this->action->on('admin:notify','error','删除失败!');
            }
            $res->goBack();
        });
    	
    	$route->get('/log', function() {
    		$this->render('log');
    	});
    });
    
    $route->group('/api',function($route){
    	
    	$route->on('auth')->get('/role', function($req, $res) {
    		$uid = model('Account')->uid();
    		$res->json(model('Users')->getUserLevel($uid));
    	});
    
        /**
         * 定义一个公用API接口
         * 这条路由规则将会自动匹配components目录下的Api.php文件
         */
        $route->on('auth')->post('/query/:func',function($req, $res) {
            $func = $req->param('func');
            $args = $req->post();
            if(!empty($func)){
                try {
                    $data = model('Api')->run($func,$args);
                    $res->json($data);
                } catch (Exception $e) {
                    $res->json('参数错误',false);
                }
            }
        });
    });
});