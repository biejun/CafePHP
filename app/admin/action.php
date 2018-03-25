<?php

$action->add('route:init',function(){
	$this->view->lang = 'zh-cmn-Hans';
	/* 设置默认视图，如果子路径中存在此设置，这里则会被覆盖 */
	$this->view->setView('admin');
});

/* 路由请求响应前挂载的动作 */
$action->add('route:before',function(){

});

$action->add('route:failed',function(){
	/* 发送一个404页 */
	$this->render('404',null,404);
});

/* 页面公用样式和脚本 */
$action->add('common:assets',function(){
	$args = func_get_args();
	$files = array(
		'css' => ['normalize.css','grid.css','fonts.css','table.css','checkbox.css','common.css','css/admin.css'],
		'js' => ['ajax.js','request.js','cookie.js','js/header.js','js/notify.js','js/modal.js'],
	);
	if($args){
		$arg_1 = array_shift($args);
		if(is_array($arg_1)){
			foreach ($arg_1 as $type => $value) {
				if(is_array($value)){
					array_merge($files[$type],$arg_1);
				}else{
					array_push($files[$type],$arg_1);
				}
			}
		}
		$arg_2 = array_shift($args);
		$suffixVersion = ($arg_2) ? date('YmdH') : null;
	}
	$view = $this->view;
	foreach ($files as $type => $file) {
		foreach ($file as $key => $value) {
			print_r($value);
			echo $value;
			// if(strpos($value, '/') !== false){
			// 	$view->assets[$type][] = $view->fileJoinVersion($view->viewPathJoin($value), $suffixVersion);
			// }else{
			// 	$view->assets[$type][] = $view->fileJoinVersion($view->pathJoin('assets', $type, $value), $suffixVersion);
			// }
		}
	}
	// $this->view->addCSS(['grid.css','fonts.css','table.css','checkbox.css','css/admin.css'],date('His'));
	// $this->view->addJS(['ajax.js','request.js','cookie.js','js/header.js','js/notify.js','js/modal.js'],date('His'));
});

// 定义一个消息提醒的动作
$action->add('admin:notify',function($success,$msg){
	$this->cookie->set('__admin_notify_type__',$success);
	$this->cookie->set('__admin_notify_msg__',$msg);
});

