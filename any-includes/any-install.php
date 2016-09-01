<?php
if(!defined('ABSPATH'))exit('Access denied!');

# 生成指定长度随机KEY
function getRandomKey($n = 18) {
	return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_~'), 0, $n);
}
# 写入用户表
function user_table($db_prefix){
	return "CREATE TABLE IF NOT EXISTS `".$db_prefix."user` (
				`user_id` int(11) NOT NULL AUTO_INCREMENT,
				`user_name` varchar(15) NOT NULL DEFAULT '',
				`user_password` varchar(32) NOT NULL DEFAULT '',
				`user_login_time` int(11) unsigned NOT NULL DEFAULT '0',
				`user_group` tinyint(4) unsigned NOT NULL DEFAULT '1',
			PRIMARY KEY (`user_id`),
			KEY `qq_token` (`qq_token`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
}
# 写入配置表
function config_table($db_prefix){
	return "CREATE TABLE IF NOT EXISTS `".$db_prefix."config` (
				`config_key` varchar(12) NOT NULL DEFAULT '',
				`config_value` text NOT NULL,
			UNIQUE KEY `config_key` (`config_key`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
}

$data = array();
$data['key'] = getRandomKey();
$data['path'] = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
$status = false;
# install
if(isset($_GET['do'])&&$_GET['do']=='install'){
	if($_POST){
		$db_host = $_POST['db_host'];
		$db_user = $_POST['db_user'];
		$db_password = $_POST['db_password'];
		$db_name = $_POST['db_name'];
		$db_prefix = $_POST['db_prefix'];
		$user_name = $_POST['user_name'];
		$user_password = md5($_POST['user_password']);
		$validate = $_POST['validate'];
		$time = time();
		$path = $data['path'];

		$any_db = new db( $db_host, $db_name, $db_user, $db_password, $db_prefix ,true);

		$query = array();
		$query[] = user_table($db_prefix);
		$query[] = config_table($db_prefix);
		$query[] = "INSERT INTO `".$db_prefix."user` VALUES (1,'$user_name','$user_password','$time','3','');";
		$query[] = "INSERT INTO `".$db_prefix."config` VALUES ('apps','admin'),('theme','simple'),('admin','YToyNDp7aTowO3M6NToidGl0bGUiO2k6MTtzOjg6InN1YnRpdGxlIjtpOjI7czo4OiJrZXl3b3JkcyI7aTozO3M6MTE6ImRlc2NyaXB0aW9uIjtpOjQ7czo4OiJzdGF0Y29kZSI7aTo1O3M6Njoibm90aWNlIjtpOjY7czoyOiJhZCI7aTo3O3M6MzoiaWNwIjtpOjg7czoxMToic210cF9zZXJ2ZXIiO2k6OTtzOjk6InNtdHBfcG9ydCI7aToxMDtzOjk6InNtdHBfdXNlciI7aToxMTtzOjEzOiJzbXRwX3Bhc3N3b3JkIjtzOjU6InRpdGxlIjtzOjEyOiLnq5nngrnmoIfpopgiO3M6ODoic3VidGl0bGUiO3M6MDoiIjtzOjg6ImtleXdvcmRzIjtzOjA6IiI7czoxMToiZGVzY3JpcHRpb24iO3M6MDoiIjtzOjg6InN0YXRjb2RlIjtzOjA6IiI7czo2OiJub3RpY2UiO3M6MDoiIjtzOjI6ImFkIjtzOjA6IiI7czozOiJpY3AiO3M6MDoiIjtzOjExOiJzbXRwX3NlcnZlciI7czoxODoic210cC5leG1haWwucXEuY29tIjtzOjk6InNtdHBfcG9ydCI7czoyOiIyNSI7czo5OiJzbXRwX3VzZXIiO3M6MDoiIjtzOjEzOiJzbXRwX3Bhc3N3b3JkIjtzOjA6IiI7fQ==');";

		foreach($query as $sql){
			$any_db->query($sql);
		}

		$config = "<?php\n\n";
		$config .= "define('DB_HOST','$db_host');\n";
		$config .= "define('DB_USER','$db_user');\n";
		$config .= "define('DB_PASSWORD','$db_password');\n";
		$config .= "define('DB_NAME','$db_name');\n";
		$config .= "define('DB_PREFIX','$db_prefix');\n";
		$config .= "define('ADMIN','$user_name');\n";
		$config .= "define('PATH','$path');\n";
		$config .= "define('VALIDATE','$validate');\n";
		@file_put_contents(ANYINC . 'any-config.php',$config) or die("请检查文件 any-config.php 的目录权限是否为0777!");
		$status = true;
	}
}
?>
<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>安装 - ANYPHP</title>
		<style type="text/css">
		*{
			margin: 0;
			padding: 0;
		}
		html{
			background-color: #eee;
		}
		body{
			margin: 100px auto 40px;
			padding: 30px 20px;
			font-family: Helvetica Neue,Hiragino Sans GB,"Microsoft Yahei",sans-serif;
			max-width: 700px;
			background-color: #fff
		}
		img{
			border:none;
		}
		h1{
			font-size: 21px;
			font-weight: normal;
			padding-bottom: 10px;
			border-bottom: 1px solid #ededed;
			color: #333;
			margin-bottom: 15px;
		}
		.alert{
			color: #31708f;
			padding: 10px 15px;
			background-color: #d9edf7;
			border:1px solid #bce8f1;
			margin-bottom: 10px;
		}
		.input-group{
			margin-left: -20px;
			margin-right: -20px;
			padding: 20px;
		}
		.input-group:hover{
			background-color: #F5F5F5;
		}
		.input-form,.input-addon{
			display: block;
		}
		.input-form{
			width: 100%;
			padding: 10px;
			font-size: 14px;
			border:1px solid #ddd;
			box-shadow:inset 0 1px 1px rgba(0,0,0,.075);
			transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		.input-form:focus{
			outline: 0;
			border:1px solid #ddd;
			border-left: 3px solid blue
		}
		.input-addon{
			margin-bottom: 5px;
			color: #666;
		}
		.submit{
			width: 150px;
			height: 35px;
			line-height: 35px;
			text-align: center;
			border:1px solid #3E97EB;
			border-radius: 20px;
			margin: 10px auto;
			display: block;
			font-size: 14px;
			color: #3E97EB;
			background-color: #fff;
			cursor: pointer;
		}
		.submit:hover{
			background-color: #3E97EB;
			color: white;
		}
		</style>
	</head>
	<body>
		<?php if(!$status){?>

		<h1>安装</h1>
		<div class="alert">您当前的系统环境：<?php echo PHP_OS=='WINNT'?'Windows':PHP_OS;?>&nbsp;/&nbsp;<?php echo version_compare(PHP_VERSION,'5.3.0','<')?'当前PHP版本过低，请更新版本':'PHP ',PHP_VERSION,' 适合安装'; ?></div>
		<form method="post" action="<?=$data['path']?>?do=install" onsubmit="return post_check(this)">
			<div class="input-group">
				<label class="input-addon">数据库服务器</label>
				<input class="input-form" name="db_host" value="localhost"/>
			</div>
			<div class="input-group">
				<label class="input-addon">数据库用户</label>
				<input class="input-form" name="db_user" value="root"/>
			</div>
			<div class="input-group">
				<label class="input-addon">数据库密码</label>
				<input class="input-form" name="db_password" value="root"/>
			</div>
			<div class="input-group">
				<label class="input-addon">数据库名</label>
				<input class="input-form" name="db_name" value="any_php" placeholder="需要存放表的库名"/>
			</div>
			<div class="input-group">
				<label class="input-addon">数据库表前缀</label>
				<input class="input-form" name="db_prefix" value="any_" placeholder="用于规范表命名"/>
			</div>
			<div class="input-group">
				<label class="input-addon">管理员</label>
				<input class="input-form" name="user_name" value="" placeholder="仅限英文数字及下划线"/>
			</div>
			<div class="input-group">
				<label class="input-addon">密码</label>
				<input class="input-form" name="user_password" value="" placeholder="密码不能少于6位"/>
			</div>
			<div class="input-group">
				<label class="input-addon">确认密码</label>
				<input class="input-form" name="user_password_once" value="" placeholder="再输一次"/>
			</div>
			<div class="input-group">
				<label class="input-addon">安全验证</label>
				<input class="input-form" name="validate" value="<?=$data['key']?>" placeholder="用于系统信息加密，请勿为空"/>
			</div>
			<button type="submit" class="submit">确定安装</button>
		</form>
		<script type="text/javascript">
		function post_check(form){
			if(form.user_name.value==''){
				alert('管理员不能为空!');
				form.user_name.focus();
				return false;
			}
			if(form.user_password.value<6){
				alert('管理员密码不能少于6位!');
				form.user_password.focus();
				return false;
			}
			if(form.user_password.value!==form.user_password_once.value){
				alert('两次输入的密码不一致!');
				form.user_password.focus();
				return false;
			}
			return true
		}
		</script>
		<?php }else{?>
		
		<h1>安装成功!</h1>
		祝您使用愉快!&nbsp;系统将在<span id="num">5</span>秒后自动刷新页面
		<script type="text/javascript">
		function auto_redirect(sec){
			var num = document.getElementById('num');
			num.innerText = sec;
			sec--;
			if(sec>0){
				setTimeout(function(){
					auto_redirect(sec);
				},1000);
			}else{
				location.href = '<?=$data["path"]?>';
			}
		}
		auto_redirect(5);
		</script>
		<?php }?>

	</body>
</html>