<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
	<meta charset="utf-8"/>
	<meta name="robots" content="none" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
<?php if(isset($this->assets['css'])) : ?>
<?php foreach( $this->assets['css'] as $cssPath ) :?>
	<link rel="stylesheet" type="text/css" href="<?=$cssPath;?>" />
<?php endforeach;?>
<?php endif;?>
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
	<title><?=$this->options->title;?></title>
	<script type="text/javascript" src="<?=$this->path;?>assets/js/knockout-3.4.2.js"></script>
	<!--[if lt IE 9]>
	<script src="<?=$this->path;?>assets/js/html5shiv.js"></script>
	<![endif]-->
</head>
<body class="admin-body">