(function(a){

  var form = document.getElementById('form'),
    postUrl = form.getAttribute('data-action'),
    path = form.getAttribute('data-path');

  var __csrf__ = document.getElementById('csrf').value;
  var TOKEN = form.getAttribute('data-token');

  var formModel = function(){
    var self = this;

    this.username = ko.observable('');
    this.password = ko.observable('');
    this.captcha = ko.observable('');
    this.keepStatus = ko.observable(true);

    this.captchaImage = ko.observable('');

    this.handlerError = ko.observable('');

    this.submitBtnText = ko.observable(false);

    this.refreshCaptchaImage = function() {
      self.captchaImage(path+'captcha/'+TOKEN+'/login?r='+(Math.random()));
    }

    this.submitLogin = function(){

      var username = self.username().trim(),
        password = self.password().trim(),
        captcha = self.captcha().trim(),
        keepStatus = self.keepStatus();

      self.handlerError('');

      if(username === ''){
        self.handlerError('请输入用户名');
        return;
      }
      if(!/^\w+$/.test(username)){
        self.handlerError('用户名必须由字母、数字及下划线组成');
        return;
      }
      if(password === ''){
        self.handlerError('请输入密码');
        return;
      }

      self.submitBtnText(true);

      a.http(postUrl).header('TOKEN', TOKEN).data({
        username:username
        ,password: md5(password)
        ,captcha: captcha
        ,keepStatus: keepStatus
        ,__csrf__:__csrf__
      }).post(function(res){
        if(res.success){
          window.location.href = path +'index?from='+path+'login';
        }else{
          self.handlerError(res.data);
		  self.refreshCaptchaImage();
        }
        self.submitBtnText(false);
      });
    }

    this.cleanError = function(){
      (self.handlerError() != '') && self.handlerError('');
    }
	
	self.refreshCaptchaImage();
  }

  ko.applyBindings(new formModel, form);

})(new Ajax); 