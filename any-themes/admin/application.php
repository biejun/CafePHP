<?php exit?>
<!doctype html>
<html class="html5">
<head>
<title>应用中心 - {$config.title}</title>
{import ('static') }
</head>
<body id="app">
{import ('header') }
<section>
	<nav class="toolbar">
		<h2 class="config-title">应用</h2>
	</nav>
	<div class="pd20">
		<div class="sub-title">
			<span class="fr">
				<input type="text" v-model="search" class="form-control fr" placeholder="搜索应用"/>
			</span>
			<ul>
				<li>应用列表<span v-cloak>({{apps.length}})</span></li>
			</ul>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>应用名称</th>
					<th>描述</th>
					<th>版本</th>
					<th>作者</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in apps | filterBy search in 'name' " v-cloak>
					<td v-text="row.name"></td>
					<td v-text="row.description" class="text-muted"></td>
					<td v-text="row.version"></td>
					<td v-text="row.author" class="text-muted"></td>
					<td v-if="row.special">系统应用</td>
					<td v-else>
						<a v-if="row.install" href="{{path}}admin/uninstall.html?app_name={{row.app}}">卸载</a>
						<a v-else href="{{path}}admin/install.html?app_name={{row.app}}">安装</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		active :'3',
		search:'',
		apps : {$apps}
	};
</script>
{import ('footer') }
</body>
</html>