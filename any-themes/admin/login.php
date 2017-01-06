<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="robots" content="noindex, nofollow">
	<meta name="renderer" content="webkit">
	<title>登录 - <?php echo $ui->config['title'];?></title>
	<script type="text/javascript" src="<?php echo $ui->static;?>js/vue.min.js"></script>
	<script type="text/javascript" src="<?php echo $ui->static;?>js/vue-resource.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $ui->static;?>css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $ui->root;?>style/login.css" />
	<!--[if lt IE 9]>
	<script src="<?php echo $ui->static;?>js/html5shiv.js"></script>
	<![endif]-->
	<script type="text/javascript">
		Vue.http.options.emulateJSON = true;
		
		function alertMsg(text,time){
			var time = time || 3000;
			var tip = document.getElementById('tip');
			if(!tip){
				tip = document.createElement('div');
				tip.id = 'tip';
				document.body.appendChild(tip);
			}
			tip.classList.add('slideIn');
			tip.innerText = text;
			setTimeout(function(){
				tip.classList.remove('slideIn');
				tip.classList.add('slideOut');
			},time)
		}
	</script>
</head>
<body>

<section id="app" class="login-box">
	<h1 class="logo text-center">LOGO</h1>
	<form class="form" v-on:submit.prevent="sendLoginRequest" onsubmit="return false;">
		<div class="alert alert-warning" v-show="showTip" style="display:none;" v-text="tip">
		</div>
		<div class="form-group">
			<label for="">用户名</label>
			<input type="text" v-model="user_name" class="form-control" placeholder="用户名">
		</div>
		<div class="form-group">
			<label for="">密码</label>
			<input type="password" v-model="user_password" class="form-control" placeholder="密码">
		</div>
		<button type="submit" class="btn btn-primary">登录</button>
	</form>
</section>
<script type="text/javascript">
	new Vue({
		el : '#app',
		data : {
			path : '<?php echo $ui->path ;?>',
			user_name : '',
			user_password : '',
			csrf_id : '<?php echo $ref_csrf_admin;?>',
			showTip : false,
			tip : ''
		},
		methods : {
			sendLoginRequest : function(){
				var data = {
					user_name : this.user_name.trim(),
					user_password : this.user_password.trim(),
					csrf_id : this.csrf_id
				}
				if(data.user_name==''||data.user_password==''){
					this.showTip = true;
					this.tip = '用户名或密码不能为空';
					return false;
				}
				if(data.user_password.length<6){
					this.showTip = true;
					this.tip = '密码不能小于6位';
					return false;
				}
				this.$http.post(this.path+'admin/request-login',data).then(function(response){
						console.log(response);
						var data = JSON.parse(response.body);
						if(data.status == 'error'){
							this.showTip = true;
							this.tip = data.message;
						}else{
							window.location = this.path+'admin';
						}
				});
			}
		},
		watch:{
			'user_name,user_password':function(){
				this.tip = '';
				this.showTip = false;
			}
		}
	});
</script>
</body>
</html>