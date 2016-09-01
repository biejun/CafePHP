<!doctype html>
<html class="html5">
<head>
<title>主题 - <?php echo $config['title'] ;?></title>
<?php $this->render('static');?> 
</head>
<body id="app">
<?php $this->render('header');?> 
<section>
	<nav class="toolbar">
		<h2 class="config-title">主题</h2>
	</nav>
</section>
<script type="text/javascript">
	var data = {
		path : '<?php echo $path ;?>',
		active :'4',
		current : <?php echo $current ;?>,
		themes : <?php echo $themes ;?>
	};
</script>
<?php $this->render('footer');?> 
</body>
</html>