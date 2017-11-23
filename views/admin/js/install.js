var req = new Request, ajax = new Ajax;

var randomHash = function(len){
	len = len || 32;
	var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|',
		maxLen = chars.length;
	var hash = '';
	for (var i = len - 1; i >= 0; i--) {
		hash += chars.charAt(Math.floor(Math.random() * maxLen));
	};
	return hash;
}

var viewModel = function () {

	this.step = ko.observable(function(){
		var step = 1;
		if(step){
			return (step > 3) ? 3 : step;
		}else{
			return 1;
		}
	}());

	this.title = ['数据库连接配置','创建管理员账号','安装完成'];

	this.buttonText = ['下一步','完成'];

	this.errors = ko.observableArray([]);

	this.dbname = ko.observable('coffee');
	this.dbuser = ko.observable('');
	this.dbpassword = ko.observable('');
	this.dbprefix = ko.observable('coffee_');
	this.dbcreate = ko.observable(true);
	this.dbhost = ko.observable('localhost');
	this.dbhash = ko.observable(randomHash());

	this.username = ko.observable('');
	this.password = ko.observable('');
	this.passwordonce = ko.observable('');
	this.safetycode = ko.observable('');

	this.saveConf = function(){

		this.errors([]);

		var step = this.step();

		if(step === 1){

			var data = {
				step:1,
				dbhost:this.dbhost(),
				dbname:this.dbname(),
				dbuser:this.dbuser(),
				dbpassword:this.dbpassword(),
				dbhash:this.dbhash(),
				dbprefix:this.dbprefix(),
				dbcreate:this.dbcreate()
			};

			if(data.dbname === ''){
				this.errors.push('数据库名称不能为空！');
			}
			if(data.dbuser === ''){
				this.errors.push('数据库用户名不能为空！');
			}
			if(data.dbpassword === ''){
				this.errors.push('数据库密码不能为空！');
			}

			if(this.errors().length === 0){
				ajax.post(req.path+'admin/setup-config'
					,data
					,function(res){
						if(res.success){
							this.step(2);
						}else{
							this.errors.push(res.data);
						}
					}.bind(this)
				);
			}
		}else if(step === 2){

			var data = {
				step:2,
				username:this.username(),
				password:this.password(),
				passwordonce:this.passwordonce(),
				safetycode:this.safetycode()
			}

			if(data.username === ''){
				this.errors.push('用户名不能为空!');
			}
			if(data.password === ''){
				this.errors.push('密码不能为空!');
			}

			if(this.errors().length === 0){
				ajax.post(req.path+'admin/setup-config'
					,data
					,function(res){
						if(res.success){
							alert('安装完成!');
							this.step(3);
						}else{
							this.errors.push(res.data);
						}
					}.bind(this)
				);
			}
		}
	};
}

var koModel = new viewModel();

ko.applyBindings(koModel,document.getElementById('app'));