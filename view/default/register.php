<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/sign.css')
    ->css('/default/css/sign.css', '1.0.12');
?>
<?php $this->stop() ?>

<div class="account-wrap" id="form" data-path="<?= $this->u();?>" data-token="<?=$token?>" data-bind="submit: submit">
  <div class="account-box">
    <div class="account-form">
        <div class="auth-nav">
            <div>
                <a href="<?=$this->u('')?>">logo</a>
            </div>
            <div class="auth-link">
                已有账号？ 
                <a href="<?=$this->u('login')?>">
                  直接登录
                </a>
            </div>
        </div>
	  <div class="signForm-wrapper">
		  <div class="message-box" data-bind="if:handlerError()!=''">
		  	  <div class="ui tiny warning message hidden" data-bind="css: { hidden: false }">
		  		<i class="close icon" data-bind="click:cleanError"></i>
		  		<div data-bind="text:handlerError"></div>
		  	  </div>
		  </div>
		  <div class="ui tiny form">
		    <input type="hidden" id="csrf" name="__csrf__" value="<?= $__csrf__;?>">
		    <div class="field" data-bind="css: {error: errorType() === 'username'}">
		      <label>用户名</label>
              <div class="ui labeled input">
                <label for="domain" class="ui basic label"><?=$domain?>/</label>
                <input type="text" placeholder="字母、数字及下划线" id="domain" tabindex="1" data-bind="value:username, enterkey: submit, event: { change: checkUserName }" placeholder="用户名" autocomplete="off" autofocus="autofocus">
              </div>
		    </div>
		    <div class="field" data-bind="css: {error: errorType() === 'password'}">
		      <label>密码</label>
		      <input type="password" class="input" tabindex="2" data-bind="value:password, enterkey: submit, event: { change: checkPassword }" placeholder="密码" autocomplete="off"/>
		    </div>
<!-- 			<div class="field" data-bind="css: {error: errorType() === 'confirmPassword'}">
			  <label>确认密码</label>
			  <input type="password" class="input" tabindex="2" data-bind="value: confirmPassword, enterkey: submit, event: { change: checkPassword }" placeholder="确认密码" autocomplete="off"/>
			</div> -->
		    <div class="field" data-bind="css: {error: errorType() === 'captcha'}">
		      <label>验证码</label>
			  <div class="ui right labeled input">
			    <input type="text" tabindex="3" data-bind="value:captcha, enterkey: submit, event: { change: handleErrorType }" placeholder="验证码">
			    <div class="ui basic label captcha">
			  		<img tabindex="3" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-bind="attr: {src: captchaImage}, click: refreshCaptchaImage, enterkey: refreshCaptchaImage" alt="图形验证码" title="点击刷新验证码" style="height: 2.53em!important;">
			    </div>
			  </div>
		    </div>
            <div class="field">
                <div class="ui checkbox">
                  <input type="checkbox" id="agreeTerms">
                  <label for="agreeTerms">注册即表示同意 <a href="<?=$this->u('terms')?>">用户协议</a>、<a href="<?=$this->u('privacy')?>">隐私政策</a></label>
                </div>
            </div>
		    <button class="ui fluid blue button" tabindex="5" data-bind="click:submit, css: { loading: isSubmit()}">
		      注册
		    </button>
		  </div>
	  </div>
    </div>
  </div>
</div>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/register.js')
    ->js('/default/js/register.js', '1.0.12');
?>
<?php $this->stop() ?>