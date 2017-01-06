<?php $ui->render('header');?>

<section id="container" class="content-page">

	<?php $ui->render('nav');?>

	<section class="page">

		<?php if($ui->config['debug']):?>
		<div class="alert alert-warning ml-15 mr-15 mt-20">
			<i class="icon-attention-circled"></i> 注意: 当前系统处于开发测试环境。
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
								<span class="cate">根域名</span>
								<p><?php echo $server['name'];?></p>
							</li>
							<li>
								<span class="cate">端口</span>
								<p><?php echo $server['port'];?></p>
							</li>
							<li>
								<span class="cate">系统时间</span>
								<p><?php echo $server['time'];?></p>
							</li>
							<li>
								<span class="cate">PHP版本</span>
								<p><?php echo $server['version'];?></p>
							</li>
							<li>
								<span class="cate">MYSQL版本</span>
								<p><?php echo $server['db_version'];?></p>
							</li>
							<li>
								<span class="cate">网站根目录</span>
								<p><?php echo $server['root'];?></p>
							</li>
							<li>
								<span class="cate">最大上传值</span>
								<p><?php echo $server['upload_size'];?></p>
							</li>
							<li>
								<span class="cate">当前占用内存</span>
								<p><?php echo $server['memory_usage'];?></p>
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
								<span class="cate">核心版本</span>
								<p><?php echo $server['core_version'];?></p>
							</li>
							<li>
								<span class="cate">禁用函数</span>
								<p><?php echo $server['disable_functions'];?></p>
							</li>
							<li>
								<span class="cate">服务器引擎</span>
								<p><?php echo $server['software'];?></p>
							</li>
							<li class="text-wrap">
								<span class="cate">已安装扩展</span>
								<p><?php echo $server['extensions'];?></p>
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
					<div class="info-list">
						<ul>
							<?php if(!empty($login_log)) : ?>
								<?php foreach ($login_log as $row) :?>
									<li>
										<p>
											<strong><?php echo date('Y-m-d H:i:s',$row['login_time']);?></strong>
											登录了后台，登录地点在
											<strong><?php echo $row['login_city'];?></strong>
										</p>
									</li>

								<?php endforeach;?>
								<li class="text-center"><a href="<?php echo $ui->path?>admin/clean-login-log">清空日志</a></li>
							<?php endif;?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

<?php $ui->render('footer');?>