(function(a, req) {

	var form = document.getElementById('form'),
		path = form.getAttribute('data-path');

	var __csrf__ = document.getElementById('csrf').value;
	var TOKEN = form.getAttribute('data-token');
    var postUrl = path + 'user/login';

	var formModel = function() {
		var self = this;

		this.username = ko.observable('');
		this.password = ko.observable('');
		this.captcha = ko.observable('');
		this.keepStatus = ko.observable(true);

		this.captchaImage = ko.observable('');
		this.errorType = ko.observable('');
		this.handlerError = ko.observable('');
		this.handleErrorType = function() {
			self.errorType('');
			self.handlerError('');
		}

		this.isSubmit = ko.observable(false);

		this.refreshCaptchaImage = function() {
			self.captchaImage(path + 'captcha/' + TOKEN + '/login?r=' + (Math.random()));
		}
		
		this.submitLogin = function() {

			var username = self.username().trim(),
				password = self.password().trim(),
				captcha = self.captcha().trim(),
				keepStatus = self.keepStatus();

			self.handlerError('');

			if (username === '') {
				self.handlerError('请输入用户名！');
				self.errorType('username');
				return;
			}
			if (!/^[a-zA-Z0-9_-]{4,}$/.test(username)) {
				self.handlerError('用户名仅支持字母、数字及下划线');
				self.errorType('username');
				return;
			}
			if (password === '') {
				self.handlerError('请输入密码！');
				self.errorType('password');
				return;
			}
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
				keepStatus: keepStatus,
				__csrf__: __csrf__
			}).post(function(res) {
				if (res.success) {
                    if(req.getQuery('ref')) {
                        window.location.href = req.getQuery('ref')
                    }else{
                        window.location.href = path + 'dashboard';
                    }
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

		// this.disableButton = ko.computed(function() {
		// 	return this.username() === '' || this.password() === '';
		// }, this)

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

})(new Ajax, new UrlRequest);
