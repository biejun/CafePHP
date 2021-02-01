<?php echo $this->tpl('tpl/start')?>
<style>
  html,body{
  height: 100%;
  background: #f2f2f2;
}

.account-wrap{
  position: relative;
  padding-top: 100px;
}

.account-container{
  width: 400px;
  background: #fff;
  margin: auto;
  padding: 20px;
  -webkit-box-shadow: 0 0 6px 0 rgba(0,0,0,.1);
  -moz-box-shadow: 0 0 6px 0 rgba(0,0,0,.1);
  box-shadow: 0 0 6px 0 rgba(0,0,0,.1);
}

.login-form{
  padding: 30px;
}

.login-form .form-title{
  font-size: 22px;
  color: #27374d;
  margin-bottom: 30px;
  text-align: center;
}

.login-form .form-addon{
  margin-bottom: 15px;
}

.login-form .input{
  border: 1px solid #e8e8e8;
  height: 40px;
  padding: 0 20px;
  resize: none;
  border-radius: 2px;
  width: 100%;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  display: block;
}

.login-form .input:focus{
  border-color: #3f8cef;
  outline: none;
  box-shadow: inset 0 1px 2px rgba(27,31,35,0.075), 0 0 0 0.2em rgba(63,140,239,0.2);
}

.login-form .button{
  margin-top: 30px;
  display: block;
  width: 100%;
  height: 40px;
  text-align: center;
  cursor: pointer;
  background-color: #3f8cef;
  border: none;
  color: #fff;
  font-size: 16px;
  border-radius: 2px;
}

.login-form .button:hover{
  background-color: #4384d8;
}

.error-message{
  position: relative;
  background: #f2dede;
  border: 1px solid #eed3d7;
  margin-bottom: 20px;
  color: #a94442;
  height: 40px;
  line-height: 30px;
  padding: 5px 14px;
  font-size: 14px;
  padding-left: 30px;
  font-weight: 400;
}

.error-message .close{
  position: absolute;
  top: 5px;
  left: 14px;
  font-size: 18px;
  cursor: pointer;
  display: block;
}
</style>
<div class="account-wrap" id="login" data-action="<?php echo $this->path;?>admin/account/login" data-path="<?php echo $this->path;?>" data-token="<?=$token?>">
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

<?php echo $this->tpl('tpl/scripts')?>
<script type="text/javascript" src="<?=$this->viewPath;?>/js/md5.js"></script>
<script type="text/javascript" src="<?=$this->viewPath;?>/js/login.js"></script>

<?php echo $this->tpl('tpl/end')?>