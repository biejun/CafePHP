<?php echo $this->tpl('tpl/start')?>
<style>
html,body{
  height: 100%;
  background: #eff8f7;
}

.account-wrap{
  position: relative;
  padding-left: 600px;
  height: 100%;
}

.account-wrap .account-box{
  width: 600px;
  float: left;
  margin-left: -600px;
  background-color: #fff;
  height: 100%;
  min-height: 400px;
}

.account-wrap .account-box .account-form{
  width: 60%;
  margin: 0 auto;
  height: 100%;
  display: flex;
  justify-content: center;
  flex-direction: column;
}

.signForm-wrapper{
	position: relative;
	
}

.signForm-wrapper .message-box{
	position: absolute;
	left: 0;
	right: 0;
	bottom: 105%;
}

.captcha{
  padding: 0 1px!important;
}
.captcha img{
  height: 34px;
}
</style>
<div class="account-wrap" id="form" data-action="<?php echo $this->path;?>welcome/api/login" data-path="<?php echo $this->path;?>" data-token="<?=$token?>">
  <div class="account-box">
    <div class="account-form">
	  <div class="signForm-wrapper">
		  <div class="message-box" data-bind="if:handlerError()!=''">
		  	  <div class="ui tiny warning message hidden" data-bind="css: { hidden: false }">
		  		<i class="close icon" data-bind="click:cleanError"></i>
		  		<div data-bind="text:handlerError"></div>
		  	  </div>
		  </div>
		  <div class="ui tiny form">
		    <input type="hidden" id="csrf" name="__csrf__" value="<?php echo $__csrf__;?>">
		    <div class="field">
		      <label>用户名</label>
		      <input type="text" class="input" tabindex="1" data-bind="value:username" placeholder="用户名" autofocus="autofocus" autocomplete="off"/>
		    </div>
		    <div class="field">
		      <label>密码</label>
		      <input type="password" class="input" tabindex="2" data-bind="value:password" placeholder="密码" autocomplete="off"/>
		    </div>
		    <div class="field">
		      <label>验证码</label>
		      <div class="ui action input">
		        <input type="text" tabindex="3" data-bind="value:captcha" placeholder="验证码">
		        <div class="ui button captcha">
		          <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-bind="attr: {src: captchaImage}, click: refreshCaptchaImage" alt="图形验证码" title="点击刷新验证码">
		        </div>
		      </div>
		    </div>
		    <div class="fields">
		      <div class="ten wide field">
		        <div class="ui checkbox">
		          <input type="checkbox" tabindex="4" data-bind="checked: keepStatus">
		          <label>保持我的登录状态</label>
		        </div>
		      </div>
		      <div class="six wide field text-right">
		        <a href="<?=$this->path;?>forget-password">忘记了密码？</a>
		      </div>
		    </div>
		    <button class="ui fluid blue button" disabled="disabled" tabindex="5" data-bind="disable: username() === '',click:submitLogin,text:submitBtnText() ? '登录中...':'登 录'">
		      登录
		    </button>
		  </div>
	  </div>
    </div>
  </div>
</div>

<?php echo $this->tpl('tpl/scripts')?>
<script type="text/javascript" src="<?=$this->sources('js/login.js');?>"></script>

<?php echo $this->tpl('tpl/end')?>