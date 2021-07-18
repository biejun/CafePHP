<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/layout.css')
    ->css('/admin/css/index.css', '1.0.12');
?>

<style>
	.operate-list .item{
		border-bottom: 1px solid #eaeaea;
		margin-bottom: 5px;
		
	}
	.operate-list p{
		margin-top: 5px;
		margin-bottom: 5px;
	}
</style>

<?php $this->stop() ?>

<section class="page-container">

  <?php $this->insert('header')?>
  
  <main class="page-main">
	  <div class="main-header-panel">
		<div class="header-panel__left">
			<h2>系统日志</h2>
		</div>
	  </div>
	  <div class="ui grid">
		  <div class="eight wide column" id="loggedContainer">
			 <h3>登录日志</h3>
			 <div class="ui list operate-list" data-bind="foreach:loggedLogs">
			   <div class="item">
			     <i class="iconfont icon-activity icon"></i>
			     <div class="content">
			       <div class="header" data-bind="text:time"></div>
			       <div class="description">
					   <p data-bind="text:'登录用户: ' + name"></p>
					   <p data-bind="text:'登录地点: ' + text"></p>
				   </div>
			     </div>
			   </div>
			 </div> 
		  </div>
		  <div class="eight wide column" id="operateContainer">
			  <h3>操作日志</h3>
			  <div class="ui list operate-list" data-bind="foreach:operateLogs">
			    <div class="item">
			      <i class="iconfont icon-activity icon"></i>
			      <div class="content">
			        <div class="header" data-bind="text: text"></div>
			        <div class="description">
						<p data-bind="text:'操作用户: ' + name"></p>
						<p data-bind="text:'操作时间: ' + time"></p>
					</div>
			      </div>
			    </div>
			  </div>
		  </div>
	  </div>
  </main>
</section>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/aside-nav.js')
    ->js('/admin/js/index.js', '1.0.12');
?>

<script type="text/javascript">
(function(a){
    var path = document.getElementsByTagName('meta')['path'].content;
	var viewModel = function(){
		this.limit = ko.observable(10);
		this.page = ko.observable(1);
		this.operateLogs = ko.observableArray([]);
	}
	var vm = new viewModel;

	ko.applyBindings(vm,document.getElementById('operateContainer'));

	a.http(path+'admin/api/query/operate_logs').data({
      page : vm.page()
      ,limit: vm.limit()
    }).post(
		function(res){
			if(res.success){
				vm.operateLogs(res.data);
			}
		}
	);
})(new Ajax);
</script>
<script type="text/javascript">
(function(a){
    var path = document.getElementsByTagName('meta')['path'].content;
	var viewModel = function(){
		this.limit = ko.observable(10);
		this.page = ko.observable(1);
		this.loggedLogs = ko.observableArray([]);
	}
	var vm = new viewModel;

	ko.applyBindings(vm,document.getElementById('loggedContainer'));

	a.http(path+'admin/api/query/logged_logs').data({
      page : vm.page()
      ,limit: vm.limit()
    }).post(function(res){
		if(res.success){
			vm.loggedLogs(res.data);
		}
	});
})(new Ajax);
</script>
<?php $this->stop() ?>