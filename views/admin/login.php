<?php echo $this->tpl('start');?>

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