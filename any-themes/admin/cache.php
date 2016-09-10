<?php exit?>
<!doctype html>
<html class="html5">
<head>
<title>缓存管理 - {$config.title}</title>
{import ('static') }
</head>
<body id="app">
{import ('header') }
<section>
	<nav class="toolbar">
		<h2 class="config-title">系统缓存</h2>
	</nav>
	<div class="pd20">
		<div class="sub-title">
			<span class="fr">
				<a href="{$path}admin/admin_cache.html?do=clear_cache" class="btn btn-primary">清空全部缓存 ( {{totalSize}} )</a>
			</span>
			<ul>
				<li>缓存文件列表<span v-cloak>({{files.length}})</span></li>
			</ul>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>缓存文件</th>
					<th>时间</th>
					<th>大小</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="row in files">
					<td v-text="row.path"></td>
					<td v-text="row.time"></td>
					<td v-text="row.size"></td>
				</tr>
			</tbody>
		</table>
	</div>
</section>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		active :'0',
		files : {$files},
		totalSize:'{$totalSize}'
	};
</script>
{import ('footer') }
</body>
</html>