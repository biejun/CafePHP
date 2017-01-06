<?php $ui->render('header');?>

<section id="container" class="content-page">

	<?php $ui->render('nav');?>

	<section class="page">
		<div class="mt-20">
			<div id="app" class="panel ml-15 mr-15 options">
				<header class="panel-heading">
					<h3><i class="icon-plug"></i> 应用中心</h3>
				</header>
				<div class="panel-body search">
					<span class="fr">
						<input type="text" v-model="search" class="form-control fr" placeholder="搜索应用"/>
					</span>
					<h3 class="title">应用列表 ( <span v-cloak v-text="apps.length"></span> )</h3>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>应用名称</th>
							<th>描述</th>
							<th>版本</th>
							<th>作者</th>
							<th class="text-center">操作</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="row in apps | filterBy search in 'name' " v-cloak>
							<td v-text="row.name"></td>
							<td v-text="row.description" class="text-muted"></td>
							<td v-text="row.version" class="text-muted"></td>
							<td v-text="row.author"></td>
							<td v-if="row.special" class="text-center text-muted">系统应用</td>
							<td v-else class="text-center">
								<a href="javascript:;" v-if="row.install.status=='安装'" @click="runInstall(row.app);" v-text="row.install.status"></a>
								<a href="javascript:;" v-else="row.install.status=='卸载'" v-text="row.install.status" @click="runUninstall(row.app);"></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</section>

<script type="text/javascript">
	new Vue({
		el : '#app',
		data : {
			path : '<?php echo $ui->path;?>',
			search:'',
			apps : []
		},
		created:function(){
			this.$http.get(this.path+'admin/application-store').then(function(response){
				this.apps = response.data;
			}.bind(this));
		},
		methods:{
			runInstall:function(app_name,index){
				this.$http.post(this.path+'admin/application-install',{app_name:app_name}).then(function(response){
					var data = response.data;
					if (data.status=='error') {
						alertMsg(data.message);
					}else{
						alert(data.message);
						window.location.reload();
					}
				});
			},
			runUninstall:function(app_name,index){
				this.$http.post(this.path+'admin/application-uninstall',{app_name:app_name}).then(function(response){
					var data = response.data;
					if (data.status=='error') {
						alertMsg(data.message);
					}else{
						alert(data.message);
						window.location.reload();
					}
				});				
			}
		}
	});
</script>

<?php $ui->render('footer');?>