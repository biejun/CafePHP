<!doctype html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="UTF-8">
	<title><?=(isset($subtitle)) ? $subtitle.' - ' : ''?><?=$this->site->title?></title>
	<meta name="keywords" content="<?=$this->site->keywords?>">
	<meta name="description" content="<?=$this->site->description?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
	
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/grid.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/fonts.css"/>
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>views/admin/styles/admin.css" />
	<link rel="stylesheet" type="text/css" href="<?=$this->path;?>assets/css/common.css" />
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>

<?php $this->show('assets');?>

</head>
<body>