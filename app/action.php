<?php
/* 路由中间件介绍 */

/* 路由器初始化时挂载的动作 */
$action->add('route:init',function(){
	/* 检查系统初始化安装配置 */
	$this->checkSystemInit();
	/* 设置默认主题 */
	$this->view->setView('pc');
});

/* 路由请求响应前挂载的动作 */
$action->add('route:before',function(){

});

/* 路由请求响应后挂载的动作 */
$action->add('route:after',function(){

});

/* 路由请求响应失败后挂载的动作 */
$action->add('route:failed',function(){

});