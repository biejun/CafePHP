<meta http-equiv="Content-type" content="text/html;charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<link rel="stylesheet" type="text/css" href="{$path}any-includes/statics/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="{$theme}styles/admin.css"/>
<script type="text/javascript" src="{$path}any-includes/statics/js/vue.min.js"></script>
<script type="text/javascript" src="{$path}any-includes/statics/js/vue-resource.min.js"></script>
<!--[if lt IE 9]>
<script src="{$path}any-includes/statics/js/html5shiv.js"></script>
<![endif]-->
<script type="text/javascript">
	function alert_tip(text,time){
		var time = time || 3000;
		var tip = document.getElementById('tip');
		if(!tip){
			tip = document.createElement('div');
			tip.id = 'tip';
			document.body.appendChild(tip);
		}
		tip.classList.add('slideIn');
		tip.innerText = text;
		setTimeout(function(){
			tip.classList.remove('slideIn');
			tip.classList.add('slideOut');
		},time)
	}
</script>
<?php admin_head_static();?>