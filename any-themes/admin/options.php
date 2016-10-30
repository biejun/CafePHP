<?php exit?>
<!doctype html>
<html>
<head>
<title>{$options.title} - {$config.title}</title>
{import ('static') }
</head>
<body data-bind="{$options.id}">
<section class="content-page">
	{import ('header') }
	<section class="page">
		<div class="mt-20">
			{$options.template}
		</div>
	</section>
</section>
<script type="text/javascript">
	{$options.scripts}
</script>
{import ('footer') }
</body>
</html>