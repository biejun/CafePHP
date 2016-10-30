<?php
$setting='
<div class="panel ml-15 mr-15">
	<header class="panel-heading">
		<h3>用户设置</h3>
	</header>
	<div class="panel-body">
		<form action="'.PATH.'account/post_admin_config" method="post" class="form">
			<fieldset>
				<legend>QQ登录设置</legend>
				<label for="qq_appid">开启</label>
				<div class="form-group">
					<input class="magic-checkbox" id="qq" type="checkbox" name="qq" value="1" ';
					if(@$this->config['qq']==1)$setting.=' checked="checked"';
					$setting.='/>
					<label for="qq">
						开启QQ登录功能
					</label>
				</div>
				<label for="qq_appid">APP ID</label>
				<div class="form-group">
					<input type="text" name="qq_appid" value="'.@$this->config['qq_appid'].'" class="form-control"/>
				</div>
				<label for="qq_appid">APP KEY</label>
				<div class="form-group">
					<input type="text" name="qq_appkey" value="'.@$this->config['qq_appkey'].'" class="form-control"/>
				</div>
			</fieldset>
			<fieldset>
				<legend>注册设置</legend>
				<label for="qq_appid">开启</label>
				<div class="form-group">
					<input class="magic-checkbox" id="register_status" type="checkbox" name="user_register_status" value="1" ';
					if(@$this->config['user_register_status']==1)$setting.=' checked="checked"';
					$setting.='/>
					<label for="register_status">
						开放注册
					</label>
				</div>
				<label for="qq_appid">用户名过滤<span>(使用英文逗号隔开)</span></label>
				<div class="form-group">
					<textarea name="user_name_filter" class="form-control"/>';
					if(empty($this->config['user_name_filter'])) :
						$setting .='admin,account,fuck,123456';
					else :
						$setting .=$this->config['user_name_filter'];
					endif;
					$setting.='</textarea>
				</div>
			</fieldset>
			<button type="submit" class="btn btn-primary">更新设置</button>
		</form>
	</div>
</div>';

return $setting;