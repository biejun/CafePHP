<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<section class="page-container white-bg">

  <?php echo $this->tpl('header');?>
  
  <main class="page-main" id="app">
	  <div class="main-header-panel">
	  		<div class="header-panel__left">
	  			<h2>用户</h2>
	  		</div>
	  	<div class="header-panel__right">
			<div class="ui small input mr-10">
				<input type="text" class="form-control" placeholder="搜索用户..." data-bind="value:search,valueUpdate:'keyup'" />
			</div>
			<button class="ui small primary button">创建用户</button>
	  	</div>
	  </div>
	  <table class="ui very basic small table">
	  	<thead>
			<tr>
				<th>ID</th>
				<th>用户名</th>
				<th>注册时间</th>
				<th>最后登录时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody data-bind="foreach:data">
			<tr>
				<td data-bind="text:id"></td>
				<td data-bind="text:name"></td>
				<td data-bind="text:created"></td>
				<td data-bind="text:logged"></td>
				<td></td>
			</tr>
		</tbody>
	  </table>
  </main>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/scripts-page');?>
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

	ajax.http(path+'welcome/api/query/users').data({
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