<?php

# 更丰富的管理员登录信息
function admin_user_info($html,$user){
	$user_avatar = widget('account')->get_avatar($user['user_id']);
	$html = '<a class="user_avatar" title="点击修改头像"><img src="'.$user_avatar.'"/></a>'.$html;
	return $html;
}
add_action('admin_info','admin_user_info');

# 定义后台管理管理员登录信息样式
function admin_user_style(){
	$content = '
		<style>
			.user_avatar{
				display:block;
				width:60px;
				height:60px;
				float:left;
			}
		</style>
	';
	echo $content;
}
add_action('admin_head_static','admin_user_style');