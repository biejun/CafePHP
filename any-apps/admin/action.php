<?php

Action::add('admin:info',function($html){
	
	$user = session('login_user');
	
	$html = '<div class="admin-name">
	
				<p>您好，'.$user['user_name'].'</p>
	
				<a href="javascript:;" @click="showChangePassword = true">修改密码</a>
	
				<a href="'.PATH.'admin/logout.html" title="退出登录">退出登录</a>
	
			</div>';
	return $html;
});

Action::add('admin:menu',function(){

	$menu = Widget::get('admin')->getApplicationsAdminMenu();

	foreach ($menu as $row) : ?>

		<li id="menu-item-<?php echo $row['id']?>" class="menu-item">
			<a href="javascript:;" title="<?php echo $row['name']?>">
				<i class="fr icon-angle-right"></i>
				<i class="<?php echo $row['icon']?>"></i>
				<span><?php echo $row['name']?></span>
			</a>
			<ul class="menu-item-child">
				<?php foreach ($row['menu'] as $menu) : ?>

				<li id="menu-item-<?php echo $row['id']?>-child-<?php echo $menu['id']?>">
					<a href="<?php echo $menu['url']?>"><?php echo $menu['title']?></a>
				</li>
				<?php endforeach;?>
				
			</ul>
		</li>

	<?php 
	endforeach;
});

Action::add('admin:footer',function(){
?>

<?php
});