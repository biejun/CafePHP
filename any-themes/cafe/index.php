<?php if( !defined('IS_ANY') ) exit('Access denied!'); ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="robots" content="noindex, nofollow">
	<meta name="renderer" content="webkit">
	<link rel="stylesheet" type="text/css" href="<?php echo $ui->static;?>css/common.css" />
	<title>Hello World!</title>
	<!--[if lt IE 9]>
	<script src="<?php echo $ui->static;?>js/html5shiv.js"></script>
	<![endif]-->
</head>
<body>
	<h1>Hello World!</h1>
	<p><?php echo $name;?></p>
</body>
</html>