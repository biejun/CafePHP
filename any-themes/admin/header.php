<nav class="nav">
	<div class="admin-info">
		<?php admin_info();?>
	</div>
	<div class="title">开始</div>
	<ul class="menu">
		<li id="menu-item-index" class="menu-item">
			<a href="{$path}admin/index.html" title="仪表盘">
				<i class="fr icon-angle-right"></i>
				<i class="icon-gauge"></i>
				<span>仪表盘</span>
			</a>
		</li>
		<li id="menu-item-setting" class="menu-item">
			<a href="{$path}admin/setting.html">
				<i class="fr icon-angle-right"></i>
				<i class="icon-wrench"></i>
				<span>通用设置</span>
			</a>
		</li>
		<li id="menu-item-application" class="menu-item">
			<a href="{$path}admin/application.html">
				<i class="fr icon-angle-right"></i>
				<i class="icon-plug"></i>
				<span>应用商店</span>
			</a>
		</li>
	</ul>
	<div class="title">应用</div>
	<ul class="menu">
		{foreach $menu as $row}
		<li id="menu-item-{$row.id}" class="menu-item">
			<a href="javascript:;" title="{$row.name}">
				<i class="fr icon-angle-right"></i>
				<i class="{$row.icon}"></i>
				<span>{$row.name}</span>
			</a>
			<ul class="menu-item-child">
				{foreach $row['menu'] as $menu}

				<li id="menu-item-{$row.id}-child-{$menu.id}">
					<a href="{$menu.url}&bindmenu=menu-item-{$row.id}-child-{$menu.id}">{$menu.title}</a>
				</li>
				{/foreach}
			</ul>
		</li>
		{/foreach}
	</ul>
</nav>
<header class="header">
	<h1 class="logo fl">
		<a href="{$path}admin/index.html">
			网站管理系统
		</a>
	</h1>
    <button type="button" onclick="toggleMenu();" class="menu-btn">
		<i class="icon-menu"></i>
    </button>
</header>