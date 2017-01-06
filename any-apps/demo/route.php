<?php
/*!
 *	这是一个简单的DEMO
 *
 *	教你如何新建一个应用，并通过书写简单的路由方式，完成每一个页面的请求
 *
**/

Route::get('/',function($ui){

	// 传递数据到视图
	$ui->assign("name","我的第一个应用");
	// 渲染到前端页面
	$ui->render('index');
});