<!doctype html>
<html lang="<?=$this->lang;?>">
<head>
	<meta charset="<?=$this->charset;?>"/>
	<title><?=$this->options->title;?></title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<meta name="force-rendering" content="webkit">
<?php if(isset($this->assets['css'])) : ?>
<?php foreach( $this->assets['css'] as $cssPath ) :?>
	<link rel="stylesheet" type="text/css" href="<?=$cssPath;?>" />
<?php endforeach;?>
<?php endif;?>
	<link rel="icon" href="<?=$this->path;?>favicon.ico" type="image/x-icon"/>
	<script type="text/javascript" src="<?=$this->path;?>assets/js/knockout-3.4.2.js"></script>
	<script type="text/javascript">
	var _CONFIG_ = {
		path : '<?=$this->path;?>',
		username : '<?=$this->account->name;?>'
	}
	</script>
</head>
<body>