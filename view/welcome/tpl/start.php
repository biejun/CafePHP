<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta name="robots" content="none" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="renderer" content="webkit">
  <meta name="force-rendering" content="webkit">
  <link rel="stylesheet" href="<?=$this->sources('css/semantic.min.css');?>">
  <link rel="stylesheet" href="//at.alicdn.com/t/font_2358367_tiwqwgxhk6n.css">
<?php
$this->minifyCSS([
	SOURCES.'/css/common.css',
	__DIR__ .'/../css/layout.css'
], 'v1/css/chunk-welcome.css', '1.0.0');
?>
  <link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
  <title><?=$this->account->name;?> - <?=$this->options->title?></title>
  <!--[if lt IE 9]>
  <script src="<?=$this->sources('js/html5shiv.js');?>"></script>
  <![endif]-->
</head>
<body>