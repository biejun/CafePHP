<script type="text/javascript" src="<?php echo $ui->root;?>admin.js"></script>
<script type="text/x-template" id="modal">
	<div class="modal-mask" v-show="show" transition="modal">
		<div class="modal-wrapper">
			<div class="modal-container" v-bind:style="style">
				<button type="button" class="modal-close" @click="show = false"><span aria-hidden="true">×</span></button>
			 	<slot></slot>
			</div>
		</div>
	</div>
</script>

<modal :show.sync="showChangePassword" :style="{width:'320px'}">
	<div class="form">
		<fieldset>
			<legend>修改密码</legend>
			<p v-text="errorMsg" style="font-size:12px;color:red;"></p>
			<div class="mb-10">
				<label>旧密码</label>
				<input type="password" v-model="old_password" class="form-control" />
			</div>
			<div class="mb-10">
				<label>新密码</label>
				<input type="password" v-model="new_password" class="form-control" />
			</div>
			<div class="mb-10">
				<label>确认密码</label>
				<input type="password" v-model="new_password_once" class="form-control" />
			</div>
			<button type="button" @click="changePassword" class="btn btn-primary">保存</button>
		</fieldset>
	</div>
</modal>

<script type="text/javascript">

	var modal = Vue.extend({
		props : ['show','style'],
		template:'#modal'
	});

	var app = new Vue({
		data:{
			path : '<?php echo $ui->path?>',
			showChangePassword : false,
			old_password : '',
			new_password : '',
			new_password_once : '',
			errorMsg : ''
		},
		el : 'body',
		components:{
			modal : modal
		},
		methods:{
			changePassword : function(){

				if(this.old_password.length>=6 && this.new_password.length >=6 && this.new_password_once.length>=6){
					var data = {
						old_password : this.old_password,
						new_password : this.new_password,
						new_password_once : this.new_password_once
					};
					this.$http.post(this.path+'admin/change-password',data).then(function(response){
						var data = response.data;
						//console.log(response)
						if(data.status=='success'){
							window.location.href = this.path+'admin/logout';
						}else{
							this.errorMsg = data.message;
						}
					});
				}else{
					this.errorMsg = '密码不能少于6位!';
				}
			}
		}
	});
</script>
<?php Action::on('admin:footer');?>
<script type="text/javascript">
	new Vue({
		el : '#container'
	})
</script>
</body>
</html>