<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
	<meta charset="<?=$this->charset;?>"/>
	<title><?=$this->options->title;?></title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/normalize.css" />
<?php if(isset($this->assets['css'])) : ?>
<?php foreach( $this->assets['css'] as $cssPath ) :?>
	<link rel="stylesheet" type="text/css" href="<?=$cssPath;?>" />
<?php endforeach;?>
<?php endif;?>
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/common.css" />
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
	<script type="text/javascript" src="<?=$this->path;?>assets/js/knockout-3.4.2.js"></script>
	<script type="text/javascript">
	var _CONFIG_ = {
		path : '<?=$this->path;?>'
	}
	</script>
</head>
<body>
	<div class="account-wrap" id="login" data-action="<?php echo $this->path;?>admin/account/login" data-path="<?php echo $this->path;?>">
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

<?php echo $this->tpl('scripts');?>

<?php echo $this->tpl('end');?>