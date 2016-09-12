<header class="navbar">
	<h1 class="logo">
		网站管理系统
	</h1>
	<ul class="menu">
		<app-menu :path="path"></app-menu>
	</ul>
	<ul class="user">
		<li>
			<a href="javascript:;" @click.stop.prevent="userDropDown = !userDropDown" class="user-name">
				123123
				<strong class="user-role">超级管理员</strong>
				<i :class="[!userDropDown ? 'icon-angle-down' : 'icon-angle-up']"></i>
			</a>
			<user-dropdown :path="path" :show="userDropDown" v-cloak></user-dropdown>
		</li>
	</ul>
</header>
<nav class="nav">
	<div class="menu">
		<ul>
			<li v-bind:class="{active : active == 1}">
				<a class="menu-item" href="{$path}admin/index.html" title="仪表盘">
					<i class="icon-gauge"></i>
					<div class="menu-name">仪表盘</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 2}">
				<a class="menu-item" href="{$path}admin/setting.html">
					<i class="icon-cog"></i>
					<div class="menu-name">设置</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 3}">
				<a class="menu-item" href="{$path}admin/application.html">
					<i class="icon-plug"></i>
					<div class="menu-name">应用</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 4}">
				<a class="menu-item" href="{$path}admin/theme.html">
					<i class="icon-brush"></i>
					<div class="menu-name">主题</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 5}">
				<a class="menu-item" href="{$path}admin/fontello.html">
					<i class="icon-link"></i>
					<div class="menu-name">链接</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 6}">
				<a class="menu-item" href="{$path}admin/fontello.html">
					<i class="icon-database"></i>
					<div class="menu-name">数据库</div>
				</a>
			</li>
			<li v-bind:class="{active : active == 7}">
				<a class="menu-item" href="{$path}admin/fontello.html">
					<i class="icon-flag"></i>
					<div class="menu-name">字体图标</div>
				</a>
			</li>
		</ul>
	</div>
</nav>