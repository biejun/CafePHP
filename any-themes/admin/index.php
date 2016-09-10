<?php exit?>
<!doctype html>
<html class="html5">
<head>
<title>管理中心 - {$config.title}</title>
{import ('static') }
</head>
<body id="app">
{import ('header') }
<section>
	<nav class="toolbar">
		<h2 class="config-title">仪表盘</h2>
	</nav>
	<div class="pd20 cf">
		<?php if(ANY_DEBUG){?>
		<div class="alert alert-warning">
			<i class="icon-attention-circled"></i>注意: 您的系统目前处于开发测试环境，正式部署时请将<u>index.php</u>文件中的ANY_BEBUG变量设置为false。
		</div>
		<?php } ?>
		
		<div class="form-item">
			<div class="form-addon">
				<span>服务器根域名</span>
				<p>{$server.name}</p>
			</div>
			<div class="form-addon">
				<span>服务器端口</span>
				<p>{$server.port}</p>
			</div>
			<div class="form-addon">
				<span>服务器时间</span>
				<p>{$server.time}</p>
			</div>
			<div class="form-addon">
				<span>PHP版本</span>
				<p>{$server.version}</p>
			</div>
			<div class="form-addon">
				<span>MYSQL版本</span>
				<p>{$server.db_version}</p>
			</div>
			<div class="form-addon">
				<span>网站根目录</span>
				<p>{$server.root}</p>
			</div>
			<div class="form-addon">
				<span>最大上传值</span>
				<p>{$server.upload}</p>
			</div>
			<div class="form-addon">
				<span>当前占用内存</span>
				<p>{$server.memory_usage}</p>
			</div>
			<div class="form-addon">
				<span>开发团队</span>
				<p>别小俊</p>
			</div>
			<div class="form-addon">
				<span>特别感谢</span>
				<p></p>
			</div>
			<div class="form-addon">
				<span>程序版本</span>
				<p>{$server.core_version}</p>
			</div>
			<div class="form-addon">
				<span>禁用函数</span>
				<p>{$server.disable_functions}</p>
			</div>
			<div class="form-addon">
				<span>服务器引擎</span>
				<p>{$server.software}</p>
			</div>
			<div class="form-addon text-wrap">
				<span>已安装扩展</span>
				<p>{$server.extensions}</p>
			</div>		
		</div>
	</div>
</section>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		active :'1'
	};
	//console.log(data);
</script>
{import ('footer') }
</body>
</html>