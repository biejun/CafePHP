<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="renderer" content="webkit">
  <meta name="force-rendering" content="webkit">
  <link rel="stylesheet" href="<?=$this->sources('css/semantic.min.css');?>">
  <link rel="stylesheet" href="<?=$this->sources('css/common.css');?>">
  <link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
  <title><?=isset($title) ? $title.' - ' : ''?> <?=$this->options->title?></title>
  <!--[if lt IE 9]>
  <script src="<?=$this->pathJoin('assets', 'js/html5shiv.js');?>"></script>
  <![endif]-->
</head>
<body>