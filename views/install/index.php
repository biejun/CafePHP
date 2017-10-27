<!DOCTYPE html>
<html lang="<?=$this->lang;?>">
<head>
	<meta charset="<?=$this->charset;?>"/>
	<meta name="robots" content="none" />
	<!-- HTTP 1.1 -->
	<meta http-equiv="pragma" content="no-cache">
	<!-- HTTP 1.0 -->
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
	<title>Install Project - 开始安装之旅</title>

	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->currentViewPath;?>style.css?v=<?=$suffixVersion?>" />
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
</head>
<body>

<div id="app">
	<div data-bind="text:title[step()-1]" class="post-header"></div>
	<div class="post-body">
		<div data-bind="visible:step()==1" class="post-form">
			<label>数据库名</label>
			<input type="text" data-bind="value:dbname"/>
			<em>将系统安装到哪个数据库？</em>
			<label>用户名</label>
			<input type="text" data-bind="value:dbuser" placeholder="用户名" />
			<em>您的数据库用户名。</em>
			<label>密码</label>
			<input type="password" data-bind="value:dbpassword" placeholder="密码" />
			<em>您的数据库密码。</em>
			<label>数据库主机</label>
			<input type="text" data-bind="value:dbhost">
			<em>如果localhost不能用，您通常可以从网站服务提供商处得到正确的信息。</em>
			<label>表前缀</label>
			<input type="text" data-bind="value:dbprefix"/>
			<label>数据加密密钥</label>
			<input type="text" verify data-bind="value:dbhash"/><a href="javascript:;" button data-bind="click:function(){dbhash(randomHash())}">换一个</a>
			<label>是否创建数据库</label>
			<input type="checkbox" data-bind="checked:dbcreate"/>
			<em data-bind="text:'如果您的数据库服务器中不存在'+dbname()+'数据库，我们将会为您自动创建一个。'"></em>
		</div>
		<div data-bind="visible:step()===2" class="post-form">
			<label>用户名</label>
			<input type="text" data-bind="value:username"/>
			<em>用户名只能含有字母、数字及下划线。</em>
			<label>密码</label>
			<input type="password" data-bind="value:password"/>
			<em>密码必须包含字母和数字，并且不能小于六位的复杂组合。</em>
			<label>确认密码</label>
			<input type="password" data-bind="value:passwordonce"/>
			<em>再输一次。</em>
			<label>安全码</label>
			<input type="password" data-bind="value:safetycode" />
			<em>用于后台操作的安全验证。</em>
		</div>
		<div data-bind="visible:step()===3" class="post-form">
			安装完成，<a href="<?=$this->path;?>admin/login" title="管理后台">进入管理后台</a>。
		</div>
		<div data-bind="visible:step()<3">
			<div data-bind="foreach:errors()" class="post-errors">
				<div data-bind="text:$data"></div>
			</div>
			<div class="post-buttons">
				<button type="button" data-bind="text:buttonText[step()-1],click:saveConf"></button>
				<button type="button" data-bind="visible:(step()==2),click:function(){step(1)},text:'上一步'"></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=$this->path;?>assets/js/knockout-3.4.2.js"></script>
<script type="text/javascript" src="<?=$this->path;?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?=$this->path;?>assets/js/cookie.js"></script>
<script type="text/javascript" src="<?=$this->path;?>assets/js/request.js"></script>

<script type="text/javascript">

	var req = new Request();

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
			var step = parseInt(req.getQuery('step'));
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
					ajax.post(req.path+'/setup-config'
						,data
						,function(res){
							res = ajax.jsonParse(res);
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
					ajax.post(req.path+'/setup-config'
						,data
						,function(res){
							res = ajax.jsonParse(res);
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

	koModel.step.subscribe(function(v){
		window.location = '?step='+v;
	});

</script>
</body>
</html>