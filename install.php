<?php

// 系统目录

define( 'ABSPATH' , dirname(__FILE__).DIRECTORY_SEPARATOR );

define( 'ANYAPP' , ABSPATH . 'any-apps' . DIRECTORY_SEPARATOR );

define( 'ANYTHEME' , ABSPATH . 'any-themes' . DIRECTORY_SEPARATOR );

define( 'ANYINC' , ABSPATH . 'any-includes' . DIRECTORY_SEPARATOR );

// 系统变量

define( 'IS_ANY' , true );

include ANYINC . 'Core.php';

$core = new Core();

# 写入用户表
function user_table($db_prefix){
	return "CREATE TABLE IF NOT EXISTS `".$db_prefix."user` (
				`user_id` int(11) NOT NULL AUTO_INCREMENT,
				`user_name` varchar(15) NOT NULL DEFAULT '',
				`user_password` varchar(32) NOT NULL DEFAULT '',
				`user_reigster_time` int(11) unsigned NOT NULL DEFAULT '0',
				`user_login_time` int(11) unsigned NOT NULL DEFAULT '0',
				`user_group` tinyint(4) unsigned NOT NULL DEFAULT '1',
			PRIMARY KEY (`user_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
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

$data['key'] = get_random_key();

$data['path'] = str_replace('install.php','',$_SERVER['SCRIPT_NAME']);

$status = false;

if(isset($_GET['do'])&&$_GET['do']=='install'){

	if($_POST){

		$array = [];

		$array['debug']				= ($_POST['debug']==1) ? true : false;
	
		$array['db']['driver']		= $_POST['driver']; // mysql | mysqli | pdo
	
		$array['db']['host'] 		= $_POST['db_host'];
	
		$array['db']['name'] 		= $_POST['db_name'];
	
		$array['db']['user'] 		= $_POST['db_user'];
	
		$array['db']['password']	= $_POST['db_password'];
	
		$array['db']['prefix']		= $_POST['db_prefix'];

		$array['validate']			= $_POST['validate'];

		$array['path']				= $data['path'];

		$array['admin']				= $_POST['user_name'];

		$array['theme']				= 'cafe';

		$user_name = $array['admin'];

		$db_prefix = $array['db']['prefix'];
		
		$user_password = md5($_POST['user_password'].$array['validate']);
		
		$register_time = time();

		$any_db = DataBase::factory( $array['db'],true );

		
		$query = array();
		
		$query[] = user_table($db_prefix);
		
		$query[] = config_table($db_prefix);
		
		$query[] = "INSERT INTO `".$db_prefix."user` VALUES (1,'$user_name','$user_password','$register_time',0,'3');";
		
		$query[] = "INSERT INTO `".$db_prefix."config` VALUES ('apps','admin'),('theme','cafe'),('admin','YToyNjp7aTowO3M6NToidGl0bGUiO2k6MTtzOjg6InN1YnRpdGxlIjtpOjI7czo4OiJrZXl3b3JkcyI7aTozO3M6MTE6ImRlc2NyaXB0aW9uIjtpOjQ7czo4OiJzdGF0Y29kZSI7aTo1O3M6Njoibm90aWNlIjtpOjY7czoyOiJhZCI7aTo3O3M6MzoiaWNwIjtpOjg7czoxMToic210cF9zZXJ2ZXIiO2k6OTtzOjk6InNtdHBfcG9ydCI7aToxMDtzOjk6InNtdHBfdXNlciI7aToxMTtzOjEzOiJzbXRwX3Bhc3N3b3JkIjtpOjEyO3M6MTA6InNtdHBfZW1haWwiO3M6NToidGl0bGUiO3M6MTI6IuermeeCueagh+mimCI7czo4OiJzdWJ0aXRsZSI7czowOiIiO3M6ODoia2V5d29yZHMiO3M6MDoiIjtzOjExOiJkZXNjcmlwdGlvbiI7czowOiIiO3M6ODoic3RhdGNvZGUiO3M6MDoiIjtzOjY6Im5vdGljZSI7czowOiIiO3M6MjoiYWQiO3M6MDoiIjtzOjM6ImljcCI7czowOiIiO3M6MTE6InNtdHBfc2VydmVyIjtzOjE4OiJzbXRwLmV4bWFpbC5xcS5jb20iO3M6OToic210cF9wb3J0IjtzOjI6IjI1IjtzOjk6InNtdHBfdXNlciI7czowOiIiO3M6MTM6InNtdHBfcGFzc3dvcmQiO3M6MDoiIjtzOjEwOiJzbXRwX2VtYWlsIjtzOjA6IiI7fQ==');";

		foreach($query as $sql){
			
			$any_db->query($sql);
		}

		$config = "<?php\nif(!defined('IS_ANY'))exit('Access denied!');\n";

		$config .= "define('VALIDATE','".$array['validate']."');\n\n";

		$config .= "define('PATH','".$array['path']."');\n\n";

		$config .= "return ".var_export($array,true).";";

		file_put_contents(ANYINC.'Config.php',$config,LOCK_EX) or die("请检查any-includes目录权限是否可写，或修改目录权限为0777!");

		// Apache Rewrite 文件
		
		$file = fopen('.htaccess', 'wb');
		
		$content = '
<IfModule mod_rewrite.c>
Options +FollowSymlinks
RewriteEngine On
RewriteBase ' . $data['path'] . '
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>';
		
		fwrite($file, $content);

		// Nginx Rewrite 文件

		$file = fopen('anyphp.conf', 'wb');
		
		$content = '
location '.$data['path'].' {
	if (-f $request_filename/index.php){
	    rewrite (.*) $1/index.php;
	}
	if (!-f $request_filename){
	    rewrite (.*) /index.php;
	}
}';
		
		fwrite($file, $content);
		
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
			font-family: "Microsoft Yahei",sans-serif;
			max-width: 700px;
			background-color: #fff
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
			background-color: #F0F0F0;
		}
		.input-form,.input-addon{
			display: block;
		}
		.select{
			display:inline-block;
			padding:6px 12px;
			font-size:14px;
			color:#555;
			background-color:#fff;
			background-image:none;
			border:1px solid #ccc;
			border-radius:4px;
			*display: inline;
			*zoom: 1;
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
			border-left: 3px solid #3E97EB;
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
			border-radius: 3px;
			margin: auto;
			display: block;
			margin-top: 20px;
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
		<div class="alert">您当前的系统环境：<?php echo PHP_OS=='WINNT'?'Windows':PHP_OS;?>&nbsp;/&nbsp;<?php echo version_compare(PHP_VERSION,'5.4.0','<=')?'当前PHP版本过低，请更新版本':'PHP ',PHP_VERSION,' 适合安装'; ?></div>
		<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>?do=install" onsubmit="return post_check(this)">
			<div class="input-group">
				<label class="input-addon">调试模式</label>
				<select name="debug" class="select">
					<option value="1">开启</option>
					<option value="0">关闭</option>
				</select>
			</div>
			<div class="input-group">
				<label class="input-addon">数据库类型</label>
				<select name="driver" class="select">
					<option value="mysqli">Mysqli 原生扩展版（推荐）</option>
					<option value="mysql">Mysql 原生</option>
				</select>
			</div>
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
				<input type="password" class="input-form" name="user_password" value="" placeholder="密码不能少于6位"/>
			</div>
			<div class="input-group">
				<label class="input-addon">确认密码</label>
				<input type="password" class="input-form" name="user_password_once" value="" placeholder="再输一次"/>
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
			if(form.user_password.value.length<6){
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