<?php
/**
 * Install Project!
 *
 * @code biejun <biejun@anyjs.org>
 * @date 2017-07-16
 */

if(version_compare(PHP_VERSION, '5.4.0', '<')) {

	die('The PHP version must be greater than 5.4.0!');
}

require __DIR__ .'/coffee/Bootstrap.php';

use Coffee\Http\Request;
use Coffee\Http\Response;
use Coffee\Database\DB;
use Coffee\Support\Str;

header( "Content-type: text/html; charset=utf-8" );

function returnMsg($code){
	// 后端校验
	switch ($code) {
		case '1000':
			$msg = '数据库用户或密码不能为空!';
			break;
		case '1001':
			$msg = '数据库创建失败!';
			break;
		case '1002':
			$msg = '请检查config目录属性权限是否为0777可写';
			break;
		case '1003':
			$msg = '缺少系统默认sql文件!';
			break;
		case '1004':
			$msg = '两次输入的密码不一致!';
			break;
		case '1005':
			$msg = '密码不能少于6位!';
			break;
		case '1006':
			$msg = '用户名或密码不能为空!';
			break;
		case '2000':
			$msg = '数据库表写入成功!';
			break;
		default:
			$msg = '无效的错误码';
			break;
	}
	return $msg;
}

function rewrite($path){

	// Apache Rewrite 文件
	$file = fopen('.htaccess', 'wb');

	$content = '
<IfModule mod_rewrite.c>
	Options +FollowSymlinks
	RewriteEngine On
	RewriteBase ' . $path . '
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>';

	fwrite($file, $content);

	// Nginx Rewrite 文件
	$file = fopen('nginx.conf', 'wb');

	$content = '
location '.$path.' {
	if (-f $request_filename/index.php){
	    rewrite (.*) $1/index.php;
	}
	if (!-f $request_filename){
	    rewrite (.*) /index.php;
	}
}';

	fwrite($file, $content);
}

$req = new Request();

$res = new Response();

$step = $req->get('step',1);

if( 1 == $step ){

	if($req->isPost()){

		extract($req->post());

		if(!empty($host) && !empty($user) && !empty($password) && !empty($name)){

			$array = array();

			$array['database']['driver'] = $driver;
			$array['database']['host'] = $host;
			$array['database']['name'] = $name;
			$array['database']['user'] = $user;
			$array['database']['password'] = $password;
			$array['database']['port'] = null;
			$array['database']['prefix'] = $prefix;
			$array['database']['charset'] = 'utf8';
			$array['database']['create'] =  (bool) $create;
			$array['cache']['location'] = 'cache/datastore';

			$file = "<?php\n return ".var_export($array,true).";";

			if(!file_put_contents('config/database.php',$file,LOCK_EX)){
				$res->redirect('?step=1&code=1001');
			}
			$res->redirect('?step=2');
		}else{
			$res->redirect('?step=1&code=1000');
		}

	}
}else if( 2 == $step ){

	$salt = Str::quickRandom(18);

	if($req->isPost()){

		extract($req->post());

		if(!empty($username) && !empty($password) && !empty($password_once)){

			if(strlen($password)<6){
				$res->redirect('?step=2&code=1005');
			}

			if($password !== $password_once){
				$res->redirect('?step=2&code=1004');
			}

			$sql = file_get_contents('install/mysql.sql');

			if($sql){

				$db = (new DB( conf('database') ))->connect( conf('database','create') );

				$sql = str_replace('any_',conf('database','prefix'), $sql);
				$sql = str_replace('%charset%',conf('database','charset'), $sql);
				$sql = explode(';', $sql);

				foreach ($sql as $query) {
					$query = trim($query);
					if ($query) {
						$db->query($query);
					}
				}

				$db->insert('users',[
					'name' => trim($username),
					'created' => $_SERVER['REQUEST_TIME'],
					'password'=> password_hash($password,PASSWORD_BCRYPT),
					'group'=> $group
				]);

				rewrite( $path );

				$array = array();

				$array['system']['timezone'] = 'PRC';
				$array['system']['charset'] = 'utf-8';
				$array['system']['debug'] = true;
				$array['system']['path'] = $path;
				$array['system']['salt'] = $salt;

				$file = "<?php\n return ".var_export($array,true).";";

				if(!file_put_contents('config/system.php',$file,LOCK_EX)){
					$res->redirect('?step=1&code=1001');
				}

				$installFile = md5(Str::quickRandom(10)) . '.php';

				if( rename( 'install.php', $installFile) ){
					# 若要重新安装，将文件重命名为install.php即可
					$res->redirect($installFile.'?step=2&code=2000&status=success');
				}

			}else{
				$res->redirect('?step=2&code=1003');
			}
		}else{
			$res->redirect('?step=2&code=1006');
		}
	}
}
?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="zh-CN">
	<meta charset="<?php echo conf('system','charset'); ?>" />
	<title>快速安装</title>
	<link rel="stylesheet" type="text/css" href="assets/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/common.css" />
	<style>
		html,body{
			background: #f2f2f2;
		}

		.container{
			width: 500px;
			margin: 60px auto;
			background: #fff;
			padding: 20px;
		}

		h3{
			margin: 0;
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 15px;
		}

		.form-addons{
			margin-bottom: 20px;
		}

		.form-addons > label{
			font-weight: bold;
			display: block;
			font-size: 12px;
			height: 20px;
			color: #555;
		}

		.form-addons > .form-input{
			padding: 5px 10px;
			border: 1px solid #e3e3e3;
			width: 100%;
			border-radius: 2px;
			font-size: 14px;
			line-height: 14px;
		}

		.form-submit{
			padding: 6px 12px;
			cursor: pointer;
			font-size: 12px;
			color: #fff;
			background-color: #4395ff;
			border:1px solid #2481fe;
			border-radius: 2px;
		}
		.form-submit:hover{
			background-color: #4890ef;
		}

		.message-box{
			padding:10px 15px;
			font-size: 12px;
			margin-bottom: 15px;
			font-weight: bold;
			border-radius: 2px;
			display: none;
		}

		.message-box.success{
			background-color: #d9ebff;
			color:#366aa4;
			display: block;
		}

		.message-box.success a{
			color: #25507f;
			margin-left: 5px;
		}

		.message-box.error{
			background-color: #ffd9d9;
			color:#c94b4b;
			display: block;
		}

		.back{
			font-size: 12px;
			text-decoration: none;
			color: #999;
			margin-left: 3px;
		}

		.back:hover{
			color: #2481fe;
		}
	</style>
</head>
<body>
	<div class="container">

	<?php if(1 == $step) : ?>

		<h3>数据库配置</h3>
		<div id="messageBox" class="message-box<?php if($req->get('code')):?> error<?php endif;?>">
			<?php if($req->get('code')):?>
				<?php echo returnMsg($req->get('code')); ?>
			<?php endif;?>
		</div>
		<form method="post" action="?step=1" onsubmit="return validate_form(this)">
			<div class="form-addons">
				<label for="">数据库驱动</label>
				<select class="form-input" name="driver" value="<?php echo conf('database','driver');?>">
					<option value="mysqli">MySQLi</option>
				</select>
			</div>
			<div class="form-addons">
				<label for="">数据库服务器</label>
				<input type="text" class="form-input" name="host" value="<?php echo conf('database','host');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">数据库服务器</label>
				<input type="text" class="form-input" name="host" value="<?php echo conf('database','host');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">数据库名称</label>
				<input type="text" class="form-input" name="name" value="<?php echo conf('database','name');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">数据库用户名</label>
				<input type="text" class="form-input" name="user" value="<?php echo conf('database','user');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">数据库密码</label>
				<input type="password" class="form-input" name="password" value="<?php echo conf('database','password');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">数据库表前缀</label>
				<input type="text" class="form-input" name="prefix" value="<?php echo conf('database','prefix');?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">是否自动创建数据库</label>
				<input type="checkbox" name="create" value="1" checked="checked">
			</div>
			<button type="submit" class="form-submit">下一步</button>
		</form>

		<script type="text/javascript">
		/*
		 * 验证表单
		 */
		function validate_form(o){

			var messageBox = document.getElementById('messageBox');

			var host = o.host.value,
				name = o.name.value,
				user = o.user.value,
				password = o.password.value,
				prefix = o.prefix.value;

			messageBox.className = 'message-box';

			if(name === ''){
				messageBox.innerText = '请填写数据库名称';
				messageBox.classList.add('error');
				o.name.focus();
				return false;
			}

			if(user === ''){
				messageBox.innerText = '请填写数据库用户名';
				messageBox.classList.add('error');
				o.user.focus();
				return false;
			}

			if(password === ''){
				messageBox.innerText = '请填写数据库密码';
				messageBox.classList.add('error');
				o.password.focus();
				return false;
			}

			return true;
		}

		</script>

	<?php elseif (2 == $step) : ?>

		<?php if($req->get('status')):?>

		<h3>安装成功</h3>
		<div id="messageBox" class="message-box success">
			<p>生成系统配置文件...成功</p>
			<p>生成伪静态文件...成功</p>
			<p>写入数据库表...成功</p>
			<p>写入管理员权限...成功</p>
			<p>安装完成，系统将在<span id="num"></span>秒后自动<a href="<?php echo conf('system','path');?>">返回首页</a></p>
		</div>

		<script>
			function auto_redirect(sec){
				var num = document.getElementById('num');
				num.innerText = sec;
				if(--sec>0){
					setTimeout(function(){
						auto_redirect(sec);
					},1000);
				}else{
					location.href = "<?php echo conf('system','path');?>";
				}
			}
			auto_redirect(5);
		</script>

		<?php else : ?>

		<h3>站点配置</h3>
		<div id="messageBox" class="message-box<?php if($req->get('code')):?> error<?php endif;?>">
			<?php if($req->get('code')):?><?php echo returnMsg($req->get('code')); ?><?php endif;?>
		</div>
		<form method="post" action="?step=2" onsubmit="return validate_form(this)">
			<div class="form-addons">
				<label for="">用户名</label>
				<input type="text" class="form-input" name="username" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">密码</label>
				<input type="password" class="form-input" name="password" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">确认密码</label>
				<input type="password" class="form-input" name="password_once" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">用户组</label>
				<select name="group" class="form-input">
					<option value="1">普通用户</option>
					<option value="2">会员用户</option>
					<option value="3" selected>管理组</option>
				</select>
			</div>
			<div class="form-addons">
				<label for="">站点目录</label>
				<input type="text" class="form-input" name="path" value="<?php echo str_replace('install.php','',$_SERVER['SCRIPT_NAME']);?>" onchange="input_trim(this);">
			</div>
			<div class="form-addons">
				<label for="">站点密钥</label>
				<input type="text" class="form-input" name="salt" value="<?php echo $salt;?>" onchange="input_trim(this);">
			</div>
			<button type="submit" class="form-submit">安装</button>
			<a href="?step=1" class="back">上一步</a>
		</form>

		<script type="text/javascript">
		/*
		 * 验证表单
		 */
		function validate_form(o){

			var messageBox = document.getElementById('messageBox');

			var username = o.username.value,
				password = o.password.value,
				password_once = o.password_once.value,
				usergroup = o.usergroup.value;

			messageBox.className = 'message-box';

			if(username === ''){
				messageBox.innerText = '用户名不能为空';
				messageBox.classList.add('error');
				o.username.focus();
				return false;
			}

			if(password === '' || password.length < 6){
				messageBox.innerText = (password.length < 6) ? '密码不能少于6位' :'密码不能为空';
				messageBox.classList.add('error');
				o.password.focus();
				return false;
			}

			if(password !== password_once){
				messageBox.innerText = '两次输入的密码不一致';
				messageBox.classList.add('error');
				o.password_once.focus();
				return false;
			}

			return true;
		}

		</script>

		<?php endif; ?>

	<?php endif; ?>
	</div>
	<script type="text/javascript">
	function input_trim(o){
		o.value = o.value.trim();
	}
	</script>
</body>
</html>