<?php

Action::add('admin:setting',function() use($config) { ?>

	<div class="width">
		<div class="panel ml-15 mr-15">
			<header class="panel-heading">
				<h3>网站基础信息配置</h3>
			</header>
			<div class="panel-body">
				<form action="<?php echo PATH;?>admin/update-admin-config" method="post" class="config-set">
					<fieldset>
						<legend>网站设置</legend>
						<div class="form-group">
							<label for="title" class="width-12-3">站点标题</label>
							<input type="text" name="title" placeholder="站点标题" class="form-control width-12-5" value="<?php echo $config['title'];?>">
						</div>
						<div class="form-group">
							<label for="subtitle" class="width-12-3">站点副标题</label>
							<input type="text" name="subtitle" placeholder="一句话描述这个站点" class="form-control width-12-5" value="<?php echo $config['subtitle'];?>">
						</div>
						<div class="form-group">
							<label for="keywords" class="width-12-3">站点关键字</label>
							<input type="text" name="keywords" placeholder="使用英文逗号隔开" class="form-control width-12-5" value="<?php echo $config['keywords'];?>">
						</div>
						<div class="form-group">
							<label for="description" class="width-12-3">站点描述</label>
							<textarea class="form-control width-12-5" name="description" placeholder="200字以内的描述" rows="3"><?php echo $config['description'];?></textarea>
						</div>
						<div class="form-group">
							<label for="notice" class="width-12-3">站点公告</label>
							<textarea name="notice" class="form-control width-12-5" placeholder="公告内容"><?php echo $config['notice'];?></textarea>
						</div>
						<div class="form-group">
							<label for="icp" class="width-12-3">网站备案号</label>
							<input type="text" name="icp" placeholder="鄂ICP备00000000号" class="form-control width-12-5" value="<?php echo $config['icp'];?>">
						</div>
						<div class="form-group">
							<label for="statcode" class="width-12-3">网站统计代码</label>
							<textarea class="form-control width-12-5" name="statcode" placeholder="粘贴第三方统计代码" rows="3"><?php echo $config['statcode'];?></textarea>
						</div>
						<div class="form-group">
							<label for="notice" class="width-12-3">广告代码</label>
							<textarea name="ad" class="form-control width-12-5" placeholder="粘贴广告代码" rows="3"><?php echo $config['ad'];?></textarea>
						</div>
					</fieldset>

					<fieldset>
						<legend>邮件服务器设置</legend>
						<div class="form-group">
							<label class="width-12-3">邮件服务器主机</label>
							<input type="text" name="smtp_server" placeholder="smtp.exmail.qq.com" class="form-control width-12-5" value="<?php echo $config['smtp_server'];?>">
						</div>
						<div class="form-group">
							<label class="width-12-3">邮件服务器端口</label>
							<input type="text" name="smtp_port" class="form-control width-12-5" value="<?php echo $config['smtp_port'];?>">
						</div>
						<div class="form-group">
							<label class="width-12-3">邮件服务器账号</label>
							<input type="text" autocomplete="off" name="smtp_user" placeholder="请输入您的邮箱账号" class="form-control width-12-5" value="<?php echo $config['smtp_user'];?>">
						</div>
						<div class="form-group">
							<label class="width-12-3">邮件服务器密码</label>
							<input type="password" autocomplete="off" name="smtp_password" placeholder="请输入您的邮箱密码" class="form-control width-12-5" value="<?php echo $config['smtp_password'];?>">
						</div>
						<div class="form-group">
							<label class="width-12-3">发件邮箱</label>
							<input type="email" autocomplete="off" name="smtp_email" placeholder="请输入您的邮箱" class="form-control width-12-5" value="<?php echo $config['smtp_email'];?>">
						</div>
					</fieldset>
					<div class="form-group">
						<label class="width-12-3"></label>
						<button type="submit" class="btn btn-primary">更新设置</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php });