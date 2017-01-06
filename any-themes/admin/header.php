<?php if( !defined('IS_ANY') ) exit('Access denied!'); ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="robots" content="noindex, nofollow">
	<meta name="renderer" content="webkit">
	<title><?php echo $ui->title; ?> - <?php echo $ui->config['title'];?></title>
	<script type="text/javascript" src="<?php echo $ui->static;?>js/vue.min.js"></script>
	<script type="text/javascript" src="<?php echo $ui->static;?>js/vue-resource.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $ui->static;?>css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $ui->root;?>style/admin.css" />
	<?php Action::on('admin:header'); ?>

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
	<style type="text/css">
		.modal-mask {
			position: fixed;
			z-index: 9998;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(231, 243, 254, .5);
			display: table;
			transition: opacity .3s ease;
		}
		.modal-wrapper {
			display: table-cell;
			vertical-align: middle;
		}
		.modal-container {
			position: relative;
			width: 500px;
			margin: 0px auto;
			padding: 20px;
			background-color: #fff;
			border-radius: 5px;
			box-shadow: 0 5px 15px rgba(202, 227, 244, .6);
			transition: all .3s ease;
		}
		.modal-container .modal-close{
			position: absolute;
			right: 18px;
			top: 15px;
			background:transparent;
			border:none;
			font-size: 21px;
			font-weight: 700;
			color: rgba(0,0,0,.2);
		}
		.modal-enter, .modal-leave {
			opacity: 0;
		}
		.modal-enter .modal-container,
		.modal-leave .modal-container {
			-webkit-transform: scale(1.2);
			transform: scale(1.2);
		}
	</style>
</head>
<body data-bind="<?php echo $bindmenu; ?>">
