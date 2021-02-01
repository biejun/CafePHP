<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="renderer" content="webkit">
  <meta name="force-rendering" content="webkit">
  <link rel="stylesheet" href="<?=$this->pathJoin('assets', 'css/semantic.min.css');?>">
  
<?php
$this->minifyCSS([
	ASSETS.'/css/common.css',
	__DIR__ .'/../css/admin.css'
], 'v1/css/chunk-admin.css', '1.0.0');
?>
  <link rel="icon" href="<?=$this->pathJoin('favicon.ico');?>" type="image/x-icon"/>
  <title>后台管理中心</title>
  <!--[if lt IE 9]>
  <script src="<?=$this->pathJoin('assets', 'js/html5shiv.js');?>"></script>
  <![endif]-->
</head>
<body>