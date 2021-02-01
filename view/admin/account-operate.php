<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="fr">
				<input type="text" class="form-control" placeholder="搜索用户..." data-bind="value:search,valueUpdate:'keyup'" />
			</div>
			<h2>用户</h2>
		</div>
		<div class="table">
			<div class="thead">
				<div class="tr">
					<div class="th">ID</div>
					<div class="th">用户名</div>
					<div class="th">注册时间</div>
					<div class="th">最后登录时间</div>
					<div class="th">操作</div>
				</div>
			</div>
			<div class="tbody" data-bind="foreach:data">
				<div class="tr">
					<div class="td" data-label="ID" data-bind="text:id"></div>
					<div class="td" data-label="用户名" data-bind="text:name"></div>
					<div class="td" data-label="注册时间" data-bind="text:created"></div>
					<div class="td" data-label="最后登录时间" data-bind="text:logged"></div>
					<div class="td" data-label="操作"></div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/header-scripts');?>
<script>
(function(c,ajax){
	var path = c.path;

	var viewModel = function(){
		this.result = ko.observableArray();
		this.search = ko.observable('');
		this.data = ko.computed(function() {
			var searchText = this.search().trim();
			return this.result().filter(function(row){
				if(searchText === ''){
					return row;
				}else{
					if(row.name.indexOf(searchText) >= 0){
						return row;
					}
				}
			});
		}, this);
	}

	var vm = new viewModel;

	ko.applyBindings(vm,document.getElementById('app'));

	ajax.http(path+'admin/api/users').data({
			page : 1
			,limit: 10
		}).post(function(res){
			if(res.success){
				vm.result(res.data);
			}
		}
	);
})(_CONFIG_,new Ajax)
</script>

<?php echo $this->tpl('tpl/end');?>