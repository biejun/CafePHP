<?php
$setting='
<form action="'.PATH.'account/admin_config_update" method="post">
	<fieldset>
		<legend>QQ登录设置</legend>
		<label for="qq_switch">启用</label>
		<div class="form-group">
			<input class="magic-checkbox" type="checkbox" id="qq" name="qq" value="1"/>
			<label for="qq">
				开启QQ登录功能
			</label>
		</div>
		<label for="qq_appid">APP ID</label>
		<div class="form-group">
			<input type="text" id="qq_appid" name="qq_appid" value="" class="form-control"/>
		</div>
	</fieldset>
	<button type="submit" class="btn btn-primary">更新设置</button>
</form>';
return $setting;
?>