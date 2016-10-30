<?php exit?>
<!doctype html>
<html>
<head>
<title>登录 - {$config.title}</title>
<meta http-equiv="Content-type" content="text/html;charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<link rel="stylesheet" type="text/css" href="{$path}any-includes/statics/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="{$theme}styles/login.css"/>
<script type="text/javascript" src="{$path}any-includes/statics/js/vue.min.js"></script>
<script type="text/javascript" src="{$path}any-includes/statics/js/vue-resource.min.js"></script>
<!--[if lt IE 9]>
<script src="{$path}any-includes/statics/js/html5shiv.js"></script>
<![endif]-->
</head>
<body>
	<section id="app" class="login-box">
		<h1 class="logo text-center">LOGO</h1>
		<div class="form">
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
			<button type="button" @click="sendLoginRequest" class="btn btn-primary">登录</button>
		</div>
	</section>
	<script type="text/javascript">
	Vue.http.options.emulateJSON = true;
	new Vue({
		el : '#app',
		data : {
			path : '{$path}',
			user_name : '',
			user_password : '',
			csrf_id : '{$ref_csrf_admin}',
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
				this.$http.post(this.path+'admin/post_login_access',data).then(function(response){
					console.log(response.body)
						var data = JSON.parse(response.body);

						if(data.status == 'error'){
							this.showTip = true;
							this.tip = data.message;
						}else{
							window.location = this.path+'admin/index.html';
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
	})
	</script>
</body>
</html>