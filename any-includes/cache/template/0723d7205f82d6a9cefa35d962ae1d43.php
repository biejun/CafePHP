<!doctype html>
<html class="html5">
<head>
<title>设置 - <?php echo $config['title'] ;?></title>
<?php $this->render('static');?> 
</head>
<body id="app">
<?php $this->render('header');?> 
<section>
	<nav class="toolbar">
		<h2 class="config-title">设置</h2>
	</nav>
	<div class="pd20 cf">
		<div class="setting fl">
			<form action="<?php echo $path ;?>admin/post_admin_config" method="post">
				<fieldset>
					<legend>基本信息</legend>

					<div class="form-group">
						<label for="title">站点标题</label>
						<input type="text" name="title" placeholder="站点标题" class="form-control" value="<?php echo $config['title'] ;?>">
					</div>
					<div class="form-group">
						<label for="subtitle">站点副标题</label>
						<input type="text" name="subtitle" placeholder="一句话描述这个站点" class="form-control" value="<?php echo $config['subtitle'] ;?>">
					</div>
					<div class="form-group">
						<label for="keywords">站点关键字</label>
						<input type="text" name="keywords" placeholder="使用英文逗号隔开" class="form-control" value="<?php echo $config['keywords'] ;?>">
					</div>
					<div class="form-group">
						<label for="description">站点描述</label>
						<textarea class="form-control" name="description" placeholder="200字以内的描述" rows="3"><?php echo $config['description'] ;?></textarea>
					</div>
					<div class="form-group">
						<label for="notice">站点公告</label>
						<textarea name="notice" class="form-control" placeholder="公告内容"><?php echo $config['notice'] ;?></textarea>
					</div>
					<div class="form-group">
						<label for="icp">网站备案号</label>
						<input type="text" name="icp" placeholder="鄂ICP备00000000号" class="form-control" value="<?php echo $config['icp'] ;?>">
					</div>
					<div class="form-group">
						<label for="statcode">网站统计代码</label>
						<textarea class="form-control" name="statcode" placeholder="粘贴第三方统计代码" rows="3"><?php echo $config['statcode'] ;?></textarea>
					</div>
					<div class="form-group">
						<label for="notice">广告代码</label>
						<textarea name="ad" class="form-control" placeholder="粘贴广告代码" rows="3"><?php echo $config['ad'] ;?></textarea>
					</div>
				</fieldset>

				<fieldset>
					<legend>邮件服务器设置</legend>

					<div class="form-group">
						<label>邮件服务器主机</label>
						<input type="text" name="smtp_server" placeholder="smtp.exmail.qq.com" class="form-control" value="<?php echo $config['smtp_server'] ;?>">
					</div>
					<div class="form-group">
						<label>邮件服务器端口</label>
						<input type="text" name="smtp_port" class="form-control" value="<?php echo $config['smtp_port'] ;?>">
					</div>
					<div class="form-group">
						<label>邮件服务器账号</label>
						<input type="text" autocomplete="off" name="smtp_user" placeholder="请输入您的邮箱账号" class="form-control" value="<?php echo $config['smtp_user'] ;?>">
					</div>
					<div class="form-group">
						<label>邮件服务器密码</label>
						<input type="password" autocomplete="off" name="smtp_password" placeholder="请输入您的邮箱密码" class="form-control" value="<?php echo $config['smtp_password'] ;?>">
					</div>
					<div class="form-group">
						<label>发件邮箱</label>
						<input type="email" autocomplete="off" name="smtp_email" placeholder="请输入您的邮箱" class="form-control" value="<?php echo $config['smtp_email'] ;?>">
					</div>
				</fieldset>
				<button type="submit" class="btn btn-primary btn-sm">更新设置</button>
			</form>
		</div>
		<div class="setting fr">
			<?php echo $setting ;?>
		</div>
	</div>
</section>
<script type="text/javascript">
	var data = {
		path : '<?php echo $path ;?>',
		active :'2'
	};
</script>
<?php $this->render('footer');?> 
</body>
</html>