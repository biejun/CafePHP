<?php
return array(
	'app'=>'message',
	'name'=>'信息',
	'description'=>'站内聊天、通信工具',
	'version'=>'1.0.0',
	'author'=>'别小俊',
	'date'=>'2016.09.12',
	'icon'=>'icon-mail-alt',
	'special'=>false,
	'options'=>array(
		array('新信息','admin_add_message.php'),
		array('我的信息','admin_my_message.php')
	),
	'route'=>array(
		'/^message\/([a-z0-9_]+)$/'=>'message/:1'
	)
);