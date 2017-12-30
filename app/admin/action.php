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

$action->add('common:assets',function(){
	$this->view->addCSS(['grid.css','fonts.css','table.css','checkbox.css','css/admin.css'],date('His'));
	$this->view->addJS(['ajax.js','request.js','cookie.js','js/header.js','js/notify.js','js/modal.js'],date('His'));
});


// 定义一个消息提醒的动作
$action->add('admin:notify',function($success,$msg){
	$this->cookie->set('__admin_notify_type__',$success);
	$this->cookie->set('__admin_notify_msg__',$msg);
});