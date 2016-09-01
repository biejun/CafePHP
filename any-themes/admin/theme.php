<?php exit?>
<!doctype html>
<html class="html5">
<head>
<title>主题 - {$config.title}</title>
{import ('static') }
</head>
<body id="app">
{import ('header') }
<section>
	<nav class="toolbar">
		<h2 class="config-title">主题</h2>
	</nav>
</section>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		active :'4',
		current : {$current},
		themes : {$themes}
	};
</script>
{import ('footer') }
</body>
</html>