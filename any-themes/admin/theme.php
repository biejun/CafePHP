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
	<div class="pd20 cf">
		<div class="sub-title">
			<span class="fr">
				<input type="text" v-model="search" class="form-control fr" placeholder="搜索主题"/>
			</span>
			<ul>
				<li>主题列表<span v-cloak>({{themes.length}})</span></li>
			</ul>
		</div>
		<ul class="theme" v-cloak>
			<li :class="['theme-item fl',themeCurrent==row.themeName?'active':'']" v-for="row in themes">
				<a href="javascript:;" @click="changeTheme(row.theme_name)" class="thumb" title="{{row.description}}">
					<img v-bind:src="row.themeThumb"/>
				</a>
				<div class="desc cf">
					<h3 v-text="row.name"></h3>
					<p><span class="fl" v-text="row.author"></span><span class="fr" v-text="row.date"></span></p>				
				</div>
			</li>
		</ul>
	</div>
</section>
<script type="text/javascript">
	var data = {
		path : '{$path}',
		active :'4',
		themeCurrent : "{$current}",
		themes : {$themes}
	};
</script>
{import ('footer') }
</body>
</html>