<?php exit?>
<!doctype html>
<html>
<head>
<title>管理中心 - {$config.title}</title>
{import ('static') }
</head>
<body data-bind="menu-item-index">

<section class="content-page">
	{import ('header') }
	<section class="page">
		<?php do_action('demo','1');?>
		<?php if(ANY_DEBUG):?>
		<div class="alert alert-warning ml-15 mr-15 mt-20">
			<i class="icon-attention-circled"></i> 注意: 当前系统处于开发测试环境，正式部署时请将<u>index.php</u>文件中的ANY_BEBUG变量修改为false。
		</div>
		<?php endif; ?>
		<div class="row mt-20">
			<div class="width-2-1">
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>服务器信息</h3>
					</header>
					<div class="info-list">
						<ul>
							<li>
								<span class="cate">服务器根域名</span>
								<p>{$server.name}</p>
							</li>
							<li>
								<span class="cate">服务器端口</span>
								<p>{$server.port}</p>
							</li>
							<li>
								<span class="cate">服务器时间</span>
								<p>{$server.time}</p>
							</li>
							<li>
								<span class="cate">PHP版本</span>
								<p>{$server.version}</p>
							</li>
							<li>
								<span class="cate">MYSQL版本</span>
								<p>{$server.db_version}</p>
							</li>
							<li>
								<span class="cate">网站根目录</span>
								<p>{$server.root}</p>
							</li>
							<li>
								<span class="cate">最大上传值</span>
								<p>{$server.upload}</p>
							</li>
							<li>
								<span class="cate">当前占用内存</span>
								<p>{$server.memory_usage}</p>
							</li>
							<li>
								<span class="cate">开发团队</span>
								<p>别小俊</p>
							</li>
							<li>
								<span class="cate">特别感谢</span>
								<p></p>
							</li>
							<li>
								<span class="cate">程序版本</span>
								<p>{$server.core_version}</p>
							</li>
							<li>
								<span class="cate">禁用函数</span>
								<p>{$server.disable_functions}</p>
							</li>
							<li>
								<span class="cate">服务器引擎</span>
								<p>{$server.software}</p>
							</li>
							<li class="text-wrap">
								<span class="cate">已安装扩展</span>
								<p>{$server.extensions}</p>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="width-2-1">
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>数据统计</h3>
					</header>
				</div>
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>登录日志</h3>
					</header>
				</div>
			</div>
		</div>
	</section>
</section>
{import ('footer') }
</body>
</html>