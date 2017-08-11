<?php if(!isset($action)) exit;

// route:before 为预定义的路由中间件
$action->add('route:before',function($req,$res){
	$res->view->setTheme('admin');
});

$action->add('route:after',function($req,$res){

});

$action->add('route:failed',function($req,$res){

	$res->view->setTheme('admin')->show('404');
});

$action->add('admin:permission',function($req,$res){

	if( widget('admin@user')->isAdmin() ){
		$res->view->assign('admin_name',__session('__admin_name__'));
		$res->view->assign('suffixVersion',date('ymdHi'));
	}else{
		$res->redirect(conf('system','path'));
	}
});