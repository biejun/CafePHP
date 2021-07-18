<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/layout.css')
    ->css('/admin/css/index.css', '1.0.12');
?>
<?php $this->stop() ?>

<section class="page-container">

  <?php $this->insert('header')?>
  
  <main class="page-main" id="app">
	  <div class="main-header-panel">
	  		<div class="header-panel__left">
	  			<h2>用户</h2>
	  		</div>
	  	<div class="header-panel__right">
			<div class="ui small input mr-10">
				<input type="text" class="form-control" placeholder="搜索用户..." data-bind="value:search,valueUpdate:'keyup'" />
			</div>
			<button class="ui small primary button" data-bind="click: createUser">创建用户</button>
	  	</div>
	  </div>
	  <table class="ui very basic small table">
	  	<thead>
			<tr>
				<th>ID</th>
				<th>用户名</th>
				<th>昵称</th>
				<th>注册时间</th>
				<th>最后登录时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody data-bind="foreach:data">
			<tr>
				<td data-bind="text:uid"></td>
				<td data-bind="text:name"></td>
				<td data-bind="text:nickname"></td>
				<td data-bind="text:created"></td>
				<td data-bind="text:logged"></td>
				<td></td>
			</tr>
		</tbody>
	  </table>
  </main>
</section>

<div id="userForm" class="ui tiny modal">
	<i class="close icon"></i>
	<div class="header">创建用户</div>
	<div class="pd-20">
		<form class="ui form">
			<div class="field">
				<label>用户名</label>
				<input type="text" data-bind="value: username" placeholder="包含字母、数字及下划线">
			</div>
			<div class="field">
				<label>昵称</label>
				<input type="text" data-bind="value: nickname" placeholder="昵称">
			</div>
			<div class="field">
				<label>邮箱</label>
				<input type="text" data-bind="value: email" placeholder="@">
			</div>
			<div class="field" data-bind="css: { error: errorType() === 'password'}">
				<label>密码</label>
				<input type="password" data-bind="value: password" placeholder="密码">
			</div>
			<div class="field" data-bind="css: { error: errorType() === 'password'}">
				<label>确认密码</label>
				<input type="password" data-bind="value: confirmPassword" placeholder="再输一次密码">
			</div>
		</form>
	</div>
	<div class="actions">
		<div class="ui cancel button">取消</div>
		<div class="ui primary button" data-bind="click: submit">添加</div>
	</div>
</div>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/aside-nav.js')
    ->js('/admin/js/index.js', '1.0.12');
?>

<script>
(function(ajax){
	var path = document.getElementsByTagName('meta')['path'].content;

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
		this.createUser = function() {
			$(function() {
				$('.ui.modal')
				  .modal({
					onHide: function() {
					  console.log('dddd')
					}
				  })
				  //.modal('attach events', '.hideModal', 'hide')
				  .modal('show');
			})
		}
	}

	var vm = new viewModel;

	ko.applyBindings(vm,document.getElementById('app'));

	ajax.http(path+'admin/api/query/users').data({
			page : 1
			,limit: 10
		}).post(function(res){
			if(res.success){
				vm.result(res.data);
			}
		}
	);
	
	var UserForm = function() {
		
		var self = this;
		
		this.username = ko.observable('');
		this.nickname = ko.observable('');
		this.email = ko.observable('');
		this.password = ko.observable('');
		this.confirmPassword = ko.observable('');
		this.errorType = ko.observable('');
		
		this.submit = function() {
			
			self.errorType('');
			
			var username = self.username().trim();
			var nickname = self.nickname().trim();
			var email = self.email().trim();
			var password = self.password().trim();
			var confirmPassword = self.confirmPassword().trim();
			
			if(password !== confirmPassword) {
				self.errorType('password');
			}
			console.log(self.username())
		}
	}
	
	ko.applyBindings(new UserForm(), document.getElementById('userForm'))
})(new Ajax)
</script>

<?php $this->stop() ?>