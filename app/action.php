<?php if(!isset($action)) exit;

// route:before 为预定义的路由中间件
$action->add('route:before',function($req,$res){

	// 将站点配置映射到视图模型中
	$data = W('admin@config')->get();
	if(!empty($data)){
		$site = new stdClass;
		foreach ($data as $row) {
			$site->{$row['name']} = $row['value'];
		}
		$res->view->site = $site;
	}
});