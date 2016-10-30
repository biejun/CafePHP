<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	插件
 *
 *	定义管理后台页面的一些接口
 */

# 后台管理侧边栏，管理员登录信息钩子
function admin_info($html=''){
	$user = session('login_user');
	$html = '
		<div class="admin-name">
			<p>您好，'.$user['user_name'].'</p>
			<a href="">修改密码</a>
			<a href="'.PATH.'admin/logout.html">退出登录</a>
		</div>
	';
	$html = apply_action('admin_info',$html,$user);
	echo $html;
}
# 后台管理HEAD标签内钩子
function admin_head_static(){
	$content = do_action('admin_head_static');
	echo $content;
}
# 后台管理页面底部钩子
function admin_footer(){
	$content = do_action('admin_footer');
	echo $content;
}