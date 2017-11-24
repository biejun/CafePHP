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
	$this->view->addCSS(['grid.css','fonts.css','magic-check.css','css/admin.css']);
	$this->view->addJS(['ajax.js','request.js','cookie.js','js/header.js','js/notify.js']);
});