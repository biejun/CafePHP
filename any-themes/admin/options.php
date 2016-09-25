<?php exit?>
<!doctype html>
<html class="html5">
<head>
<title>{$options.title} - {$config.title}</title>
{import ('static') }
</head>
<body id="app">
{import ('header') }
<section>
	<nav class="toolbar">
		<h2 class="config-title">{$options.title}</h2>
	</nav>
	<div id="options" class="pd20">
		{$options.template}
	</div>
</section>
<script type="text/javascript">
	{$options.vue}
</script>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		userDropDown:false,
		active :'0'
	};
</script>
{import ('footer') }
</body>
</html>