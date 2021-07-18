<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/sign.css')
    ->css('/default/css/sign.css', '1.0.12');
?>
<?php $this->stop() ?>

<div class="account-wrap" id="form" data-path="<?= $this->u();?>" data-token="<?=$token?>" data-bind="submit: submitLogin">
<div class="account-box">
<div class="account-form">
    <div class="auth-nav">
        <div>
            <a href="<?=$this->u('')?>">logo</a>
        </div>
        <div class="auth-link">
            没有账号？ 
            <a href="<?=$this->u('register')?>">
              注册账号
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
            <input type="text" placeholder="字母、数字及下划线" id="domain" tabindex="1" data-bind="value:username, enterkey: submitLogin, event: { change: handleErrorType }" placeholder="用户名" autofocus="autofocus" autocomplete="off">
          </div>
        </div>
        <div class="field" data-bind="css: {error: errorType() === 'password'}">
          <label>密码</label>
          <input type="password" class="input" tabindex="2" data-bind="value:password, enterkey: submitLogin, event: { change: handleErrorType }" placeholder="密码" autocomplete="off"/>
        </div>
        <div class="field" data-bind="css: {error: errorType() === 'captcha'}">
          <label>验证码</label>
          <div class="ui right labeled input">
            <input type="text" tabindex="3" data-bind="value:captcha, enterkey: submitLogin, event: { change: handleErrorType }" placeholder="验证码">
            <div class="ui basic label captcha">
                <img tabindex="3" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-bind="attr: {src: captchaImage}, click: refreshCaptchaImage, enterkey: refreshCaptchaImage" alt="图形验证码" title="点击刷新验证码" style="height: 2.53em!important;">
            </div>
          </div>
        </div>
          <div class="field relative">
              <div class="forget-password" tabindex="5">
                 <a href="<?=$this->u('forget/password');?>">忘记密码？</a>
              </div>
              <div class="ui checkbox">
                <input type="checkbox" id="loginRemember" tabindex="4" data-bind="checked: keepStatus">
                <label for="loginRemember">记住我</label>
              </div>
          </div>
        <button class="ui fluid blue button " tabindex="6" data-bind=" click:submitLogin, css: { loading: isSubmit()}">
          登录
        </button>
      </div>
  </div>
</div>
</div>
</div>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/login.js')
    ->js('/default/js/login.js', '1.0.12');
?>
<?php $this->stop() ?>