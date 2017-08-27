<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<h2>网站概要</h2>
			<div class="dashboard">
				在这里显示站点统计信息
			</div>
		</div>
		<div class="item-wrap">
			<div class="row">
				<div id="logs" class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<h3>登录日志<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body" data-bind="foreach:logs">
							<div class="item-card card">
								<div class="text">
									<p>
										<span class="time" data-bind="text:time"></span>
										登录操作提示
									</p>
									<p class="info">
										<span class="mr-5" data-bind="text:'登录用户：'"></span>
										<span data-bind="text:name"></span>
									</p>
									<p class="info">
										<span class="mr-5" data-bind="text:'登录地点：'"></span>
										<span data-bind="text:city"></span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="operates" class="width-3-1">
					<div class="item-box">
						<header class="item-header">
						<button type="button" class="ribbon-button fr" data-bind="text:'清理日志'"></button>
							<h3>操作日志<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body" data-bind="foreach:operates">
							<div class="item-card card">
								<div class="text">
									<p data-bind="text:text"></p>
									<p class="info">
										<span class="mr-5" data-bind="text:'操作用户：'"></span>
										<span data-bind="text:name"></span>
									</p>
									<p class="info">
										<span class="mr-5" data-bind="text:'操作时间：'"></span>
										<span data-bind="text:time"></span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="plan" class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<button type="button" class="ribbon-button fr" data-bind="click:function(){showTextarea(true)},text:'新建任务'"></button>
							<h3>待办事项<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
							<div class="card" data-bind="visible:showTextarea,css:{'plan-textarea':true}" style="display:none">
								<textarea data-bind="value:planning" class="form-control planning" rows="4" placeholder="任务内容"></textarea>
								<div class="plan-priority">
									<div data-bind="click:changePriority">
										<span data-bind="attr:{'class':'priority level-'+level()},text:priorityText"></span>
										优先级
									</div>
									<div class="options-box" data-bind="visible:optionsBox" style="display:none">
										<ul>
											<li class="level-1" data-bind="click:selectLevel.bind($data,1,'普通'),css:{'current':level() == 1}">普通</li>
											<li class="level-2" data-bind="click:selectLevel.bind($data,2,'紧急'),css:{'current':level() == 2}">紧急</li>
											<li class="level-3" data-bind="click:selectLevel.bind($data,3,'非常紧急'),css:{'current':level() == 3}">非常紧急</li>
										</ul>
									</div>
								</div>
								<button type="button" class="plan-btn plan-submit" data-bind="click:created">创建</button>
								<button type="button" class="plan-btn plan-cancel" data-bind="click:function(){showTextarea(false)}">取消</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<script type="text/javascript">
(function(c){
	var viewModel = function(){

		this.showTextarea = ko.observable(false);
		this.planning = ko.observable('');
		this.plans = ko.observableArray();
		this.level = ko.observable(1);
		this.optionsBox = ko.observable(false);
		this.priorityText = ko.observable('普通');
		this.changePriority = function(){
			this.optionsBox(true);
		}.bind(this);
		this.selectLevel = function(i,text){
			this.level(i);
			this.priorityText(text);
			this.optionsBox(false);
		}
		this.created = function(){
			alert('这个功能还没实现');
		}
	}

	ko.applyBindings(new viewModel,document.getElementById('plan'));
})(_CONFIG_);
</script>
<script type="text/javascript">
(function(a,c){
	var viewModel = function(){

		this.limit = ko.observable(10);
		this.page = ko.observable(1);
		this.logs = ko.observableArray([]);
	}
	var vm = new viewModel,
		path = c.path;

	ko.applyBindings(vm,document.getElementById('logs'));

	a.post(path+'admin/api/logs'
		,{
			page : vm.page()
			,limit: vm.limit()
		}
		,function(res){
			res = a.jsonParse(res);
			if(res.success){
				vm.logs(res.data);
			}
		}
	);
})(ajax,_CONFIG_);
</script>
<script type="text/javascript">
(function(a,c){
	var viewModel = function(){

		this.limit = ko.observable(10);
		this.page = ko.observable(1);
		this.operates = ko.observableArray([]);
	}
	var vm = new viewModel,
		path = c.path;

	ko.applyBindings(vm,document.getElementById('operates'));

	a.post(path+'admin/api/operates'
		,{
			page : vm.page()
			,limit: vm.limit()
		}
		,function(res){
			res = a.jsonParse(res);
			if(res.success){
				vm.operates(res.data);
			}
		}
	);
})(ajax,_CONFIG_);
</script>

<?php $this->show('end');?>