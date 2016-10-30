<?php
return array(
	'app'=>'account',
	'name'=>'用户',
	'description'=>'更完善的用户信息模块，支持QQ登录，用户头像上传等',
	'version'=>'1.0.0',
	'author'=>'别小俊',
	'date'=>'2016.06.23',
	'icon'=>'icon-user',
	'special'=>false,
	'options'=>array(
		array('全部用户','admin_user.php'),
		array('用户黑名单','admin_user_blacklist.php')
	),
	'route'=>array(
		'/^account\/([a-z0-9_]+)$/'=>'account/:1'
	)
);