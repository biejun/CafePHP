<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">

	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>views/admin/styles/login.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/common.css" />
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
	<title>登录后台</title>
	<!--[if lt IE 9]>
	<script src="<?=$this->path;?>assets/js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>

	<div class="account-wrap" id="login" data-action="<?php echo $this->path;?>admin/post/login" data-path="<?php echo $this->path;?>">
		<div class="account-container">
			<form class="login-form" method="post">
				<div class="form-title">登录</div>
				<!-- ko if:handlerError()!='' -->
				<div data-bind="css:{'error-message':true}">
					<span data-bind="click:cleanError,text:'&times;'" class="close"></span>
					<span data-bind="text:handlerError"></span>
				</div>
				<!-- /ko -->
				<input type="hidden" id="csrf" name="__csrf__" value="<?php echo $__csrf__;?>">
				<div class="form-addon">
					<input type="text" class="input" data-bind="value:username" placeholder="用户名" autofocus="autofocus" autocomplete="off"/>
				</div>
				<div class="form-addon">
					<input type="password" class="input" data-bind="value:password" placeholder="密码" autocomplete="off"/>
				</div>
				<button class="button" type="submit" data-bind="click:submitLogin,text:submitBtnText() ? '登录中...':'登录'"></button>
			</form>
		</div>
	</div>
<script type="text/javascript" src="<?=$this->path;?>assets/js/knockout-3.4.2.js"></script>
<script type="text/javascript" src="<?=$this->path;?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?=$this->path;?>assets/js/request.js"></script>
<script type="text/javascript" src="<?=$this->path;?>views/admin/scripts/login.js"></script>
</body>
</html>