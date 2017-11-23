(function(a){

	var login = document.getElementById('login'),
		postUrl = login.getAttribute('data-action'),
		path = login.getAttribute('data-path');

	var __csrf__ = document.getElementById('csrf').value;

	var loginModel = function(){
		var self = this;

		this.username = ko.observable('');
		this.password = ko.observable('');

		this.handlerError = ko.observable('');

		this.submitBtnText = ko.observable(false);

		this.submitLogin = function(){

			var username = self.username().trim(),
				password = self.password().trim();

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

			a.post(postUrl,{
				username:username
				,password:password
				,__csrf__:__csrf__
			},function(res){
				if(res.success){
					window.location.href = path +'admin/index?source=admin/login';
				}else{
					self.handlerError(res.data);
				}
				self.submitBtnText(false);
			});
		}

		this.cleanError = function(){
			(self.handlerError() != '') && self.handlerError('');
		}
	}

	ko.applyBindings(new loginModel,login);

})(new Ajax);