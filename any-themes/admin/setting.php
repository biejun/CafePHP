<?php exit?>
<!doctype html>
<html>
<head>
<title>设置 - {$config.title}</title>
{import ('static') }
</head>
<body data-bind="menu-item-setting">

<section class="content-page">
	{import ('header') }
	<section class="page">
		<div class="row mt-20">
			<div class="width-2-1">
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>主题外观设置</h3>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>主题</th>
								<th>描述</th>
								<th>作者</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							{foreach $themes as $row}
							<tr>
								<td>{$row.name}</td>
								<td class="text-muted">{$row.description}</td>
								<td>{$row.author}</td>
								<td>
									{if $row['actived']}
									<a href="">已使用</a>
									{/if}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>网站基础信息配置</h3>
					</header>
					<div class="panel-body">
						<form action="{$path}admin/post_admin_config" method="post" class="form">
							<fieldset>
								<legend>网站设置</legend>
								<div class="form-group">
									<label for="title">站点标题</label>
									<input type="text" name="title" placeholder="站点标题" class="form-control" value="{$config.title}">
								</div>
								<div class="form-group">
									<label for="subtitle">站点副标题</label>
									<input type="text" name="subtitle" placeholder="一句话描述这个站点" class="form-control" value="{$config.subtitle}">
								</div>
								<div class="form-group">
									<label for="keywords">站点关键字</label>
									<input type="text" name="keywords" placeholder="使用英文逗号隔开" class="form-control" value="{$config.keywords}">
								</div>
								<div class="form-group">
									<label for="description">站点描述</label>
									<textarea class="form-control" name="description" placeholder="200字以内的描述" rows="3">{$config.description}</textarea>
								</div>
								<div class="form-group">
									<label for="notice">站点公告</label>
									<textarea name="notice" class="form-control" placeholder="公告内容">{$config.notice}</textarea>
								</div>
								<div class="form-group">
									<label for="icp">网站备案号</label>
									<input type="text" name="icp" placeholder="鄂ICP备00000000号" class="form-control" value="{$config.icp}">
								</div>
								<div class="form-group">
									<label for="statcode">网站统计代码</label>
									<textarea class="form-control" name="statcode" placeholder="粘贴第三方统计代码" rows="3">{$config.statcode}</textarea>
								</div>
								<div class="form-group">
									<label for="notice">广告代码</label>
									<textarea name="ad" class="form-control" placeholder="粘贴广告代码" rows="3">{$config.ad}</textarea>
								</div>
							</fieldset>

							<fieldset>
								<legend>邮件服务器设置</legend>

								<div class="form-group">
									<label>邮件服务器主机</label>
									<input type="text" name="smtp_server" placeholder="smtp.exmail.qq.com" class="form-control" value="{$config.smtp_server}">
								</div>
								<div class="form-group">
									<label>邮件服务器端口</label>
									<input type="text" name="smtp_port" class="form-control" value="{$config.smtp_port}">
								</div>
								<div class="form-group">
									<label>邮件服务器账号</label>
									<input type="text" autocomplete="off" name="smtp_user" placeholder="请输入您的邮箱账号" class="form-control" value="{$config.smtp_user}">
								</div>
								<div class="form-group">
									<label>邮件服务器密码</label>
									<input type="password" autocomplete="off" name="smtp_password" placeholder="请输入您的邮箱密码" class="form-control" value="{$config.smtp_password}">
								</div>
								<div class="form-group">
									<label>发件邮箱</label>
									<input type="email" autocomplete="off" name="smtp_email" placeholder="请输入您的邮箱" class="form-control" value="{$config.smtp_email}">
								</div>
							</fieldset>
							<button type="submit" class="btn btn-primary">更新设置</button>
						</form>
					</div>
				</div>
			</div>
			{if $settings}
			<div class="width-2-1">
				{$settings}
			</div>
			{/if}
		</div>
	</section>
</section>
{import ('footer') }
</body>
</html>