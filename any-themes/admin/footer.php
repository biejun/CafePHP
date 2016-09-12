<script type="text/x-template" id="userDropdown">
	<div v-show="show" class="user-dropdown" @click.stop style="display:none">
		<ul class="user-set fr">
			<li :class="{active:userSetView =='1'}">
				<a href="javascript:;" @click="userSetView = '1'">修改密码</a>
			</li>
			<li :class="{active:userSetView =='2'}">
				<a href="javascript:;" @click="userSetView = '2'">上传头像</a>
			</li>
			<li :class="{active:userSetView =='3'}">
				<a href="javascript:;" @click="userSetView = '3'">个人资料</a>
			</li>
			<li>
				<a href="javascript:;">退出登录</a>
			</li>
		</ul>
		<div v-if="userSetView =='1'" class="user-row fl">
			<div class="mb10">
				<label>旧密码</label>
				<input class="form-control" />
			</div>
			<div class="mb10">
				<label>新密码</label>
				<input class="form-control" />
			</div>
			<div class="mb10">
				<label>确认密码</label>
				<input class="form-control" />
			</div>
			<button type="button" class="btn btn-primary">保存</button>		
		</div>
		<div v-if="userSetView =='2'" class="user-row fl">
			<div class="mb10 text-center">
				<label>点击头像上传照片</label>
			</div>
			<div class="mb15 text-center">
				<div class="user-avatar">
					<!--<img src="{$path}">-->
				</div>
			</div>
			<button type="button" class="btn btn-primary">上传</button>		
		</div>
		<div v-if="userSetView =='3'" class="user-row fl">
			<div class="mb10">
				<label>昵称</label>
				<input class="form-control" />
			</div>
			<div class="mb10">
				<label>邮箱</label>
				<input class="form-control" />
			</div>
			<div class="mb10">
				<label>签名</label>
				<textarea class="form-control" /></textarea>
			</div>
			<button type="button" class="btn btn-primary">保存</button>		
		</div>
	</div>
</script>
<script type="text/javascript" src="{$theme}admin.js"></script>