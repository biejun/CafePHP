<nav class="nav">
	<div class="admin-info">
		<?php echo Action::apply('admin:info','');?>
	</div>
	<div class="title">开始</div>
	<ul class="menu">
		<li id="menu-item-index" class="menu-item">
			<a href="<?php echo $ui->path; ?>admin" title="仪表盘">
				<i class="fr icon-angle-right"></i>
				<i class="icon-gauge"></i>
				<span>仪表盘</span>
			</a>
		</li>
		<li id="menu-item-setting" class="menu-item">
			<a href="<?php echo $ui->path; ?>admin/setting.html" title="通用设置">
				<i class="fr icon-angle-right"></i>
				<i class="icon-wrench"></i>
				<span>通用设置</span>
			</a>
		</li>
		<li id="menu-item-application" class="menu-item">
			<a href="<?php echo $ui->path; ?>admin/application.html" title="应用中心">
				<i class="fr icon-angle-right"></i>
				<i class="icon-plug"></i>
				<span>应用中心</span>
			</a>
		</li>
	</ul>
	<div class="title">应用</div>
	<ul class="menu">
		<?php Action::on('admin:menu');?>
	</ul>
</nav>
<header class="header">
	<h1 class="logo fl">
		<a href="<?php echo $ui->path; ?>admin/index.html">
			
		</a>
	</h1>
    <button type="button" onclick="toggleMenu();" class="menu-btn">
		<i class="icon-menu"></i>
    </button>
</header>