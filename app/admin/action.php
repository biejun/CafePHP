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
	$this->response->status(404)->render('404');
});

/* 页面公用样式和脚本 */
$action->add('common:assets',function($files = array(), $suffixVersion = null){
	$suffixVersion = is_null($suffixVersion) ? null : date('YmdH');
	$commonFiles = array(
		'css' => ['normalize.css','fonts.css','common.css','css/admin.css'],
		'js' => ['vendor.js','request.js','js/admin.js'],
	);
	if($files){
		foreach ($files as $type => $value) {
			if( is_array($value) ) {
				$commonFiles[$type] = array_merge($commonFiles[$type],$value);
			} else {
				array_push($commonFiles[$type],$value);
			}
		}
	}
	$view = $this->view;
	foreach ($commonFiles as $type => $file) {
		foreach ($file as $key => $value) {
			if(strpos($value, '/') !== false){
				$view->assets[$type][] = $view->fileJoinVersion($view->viewPathJoin($value), $suffixVersion);
			}else{
				$view->assets[$type][] = $view->fileJoinVersion($view->pathJoin('assets', $type, $value), $suffixVersion);
			}
		}
	}
});

// 定义一个消息提醒的动作
$action->add('admin:notify',function($success,$msg){
	$this->cookie->set('__admin_notify_type__',$success);
	$this->cookie->set('__admin_notify_msg__',$msg);
});

