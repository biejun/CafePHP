<?php
return array(
	'app'=>'admin',
	'name'=>'系统管理',
	'description'=>'网站信息配置，应用安装与卸载，权限控制等',
	'version'=>'1.0.0',
	'author'=>'别小俊',
	'date'=>'2016.06.23',
	'icon'=>'icon-th',
	'special'=>true,
	'options'=>array(
		array('数据库备份','backup'),
		array('清理临时文件','files'),
		array('清空缓存','caches'),
		array('字体图标','fontello'),
	)
);