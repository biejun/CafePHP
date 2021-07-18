(function(a) {

	var form = document.getElementById('form'),
		path = form.getAttribute('data-path');

	var __csrf__ = document.getElementById('csrf').value;
	var TOKEN = form.getAttribute('data-token');
    var postUrl = path + 'user/register';

	var formModel = function() {
		var self = this;

		this.username = ko.observable('');
		this.password = ko.observable('');
		this.confirmPassword = ko.observable('');
		this.captcha = ko.observable('');

		this.captchaImage = ko.observable('');
		this.errorType = ko.observable('');
		this.handlerError = ko.observable('');
		this.handleErrorType = function() {
			self.errorType('');
			self.handlerError('');
		}
		this.isSubmit = ko.observable(false);

		this.refreshCaptchaImage = function() {
			self.captchaImage(path + 'captcha/' + TOKEN + '/register?r=' + (Math.random()));
		}
		
		this.checkUserName = function(vm) {
			var username = vm.username().trim();
			if (!/^[a-zA-Z0-9_]{4,}$/.test(username)) {
				self.handlerError('用户名仅支持字母、数字及下划线，且不少于4位');
				self.errorType('username');
				return;
			}else{
				self.handleErrorType();
				
				a.http(path+'user/check-name/'+username).get(function(res) {
					if (!res.success) {
						self.errorType('username');
						self.handlerError(res.data);
					}
				});
			}
		}
		
		this.checkPassword = function(vm) {
			var password = vm.password().trim(),
			 confirmPassword = vm.confirmPassword().trim();
			
			if(password.length === 0) {
				self.handlerError('请输入密码！');
				self.errorType('password');
				return;
			}
            
            if (password.length < 6) {
            	self.handlerError('密码过于简单！');
            	self.errorType('password');
            	return;
            }
			
			// if(confirmPassword.length === 0) {
			// 	self.handlerError('请再次输入密码！');
			// 	self.errorType('confirmPassword');
			// 	return;
			// }
			// if(password.length !== confirmPassword.length 
			//   || password !== confirmPassword) {
			// 	self.handlerError('两次输入的密码不一致！');
			// 	self.errorType('confirmPassword');
			// 	return;
			// }
			
			self.handleErrorType();
		}

		this.submit = function() {

			var username = self.username().trim(),
				password = self.password().trim(),
				confirmPassword = self.confirmPassword().trim(),
				captcha = self.captcha().trim()

			self.handlerError('');

			if (username === '') {
				self.handlerError('请输入用户名！');
				self.errorType('username');
				return;
			}
			if (!/^[a-zA-Z0-9_]{3,}$/.test(username)) {
				self.handlerError('用户名仅支持字母、数字及下划线，且不少于4位');
				self.errorType('username');
				return;
			}
			if(password.length === 0) {
				self.handlerError('请输入密码！');
				self.errorType('password');
				return;
			}
            
            if (password.length < 6) {
            	self.handlerError('密码过于简单！');
            	self.errorType('password');
            	return;
            }
			// if(password.length !== confirmPassword.length 
			// 	|| password !== confirmPassword) {
			// 	self.handlerError('两次输入的密码不一致！');
			// 	self.errorType('confirmPassword');
			// 	return;
			// }
			if (captcha === '') {
				self.handlerError('请输入验证码！');
				self.errorType('captcha');
				return;
			}
			
			self.errorType('');

			self.isSubmit(true);

			a.http(postUrl).header('TOKEN', TOKEN).data({
				username: username,
				password: md5(password),
				captcha: captcha,
				__csrf__: __csrf__
			}).post(function(res) {
				if (res.success) {
					window.location.href = path;
				} else {
					self.handlerError(res.data);
					self.refreshCaptchaImage();
				}
				self.isSubmit(false);
			});
		}

		this.cleanError = function() {
			(self.handlerError() != '') && self.handlerError('');
		}

		this.disableButton = ko.computed(function() {
			return this.username() === '' || this.password() === '';
		}, this)

		self.refreshCaptchaImage();
	}

	ko.bindingHandlers.enterkey = {
		init: function(element, valueAccessor, allBindings, viewModel) {
			var callback = valueAccessor();
			$(element).keyup(function(event) {
				var keyCode = (event.which ? event.which : event.keyCode);
				if (keyCode === 13) {
					callback.call(viewModel, event);
				}
			});
		}
	};

	ko.applyBindings(new formModel, form);

})(new Ajax);
