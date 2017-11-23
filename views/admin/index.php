<?php echo $this->tpl('start');?>

<?php echo $this->tpl('header');?>

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
							<button type="button" class="ribbon-button fr" data-bind="text:'清理日志'"></button>
							<h3>登录日志<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body" data-bind="foreach:logs">
							<div class="item-card card">
								<div class="text">
									<p>
										<span class="time" data-bind="text:time"></span>
										<!-- ko text:'登录操作提示' --><!--/ko-->
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
				<div id="plans" class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<button type="button" class="ribbon-button fr" data-bind="click:create,text:'新建任务'"></button>
							<h3>待办事项<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
							<div class="card" data-bind="visible:showTextarea,css:{'plan-textarea':true}" style="display:none">
								<textarea data-bind="value:textarea,hasFocus:textareaFocus" class="form-control planning" rows="4" placeholder="任务内容"></textarea>
								<div class="plan-priority">
									<div data-bind="click:changePriority">
										<span data-bind="attr:{'class':'priority level-'+level()},text:priorityText"></span>
										优先级
									</div>
									<div class="options-box" data-bind="visible:optionsBox" style="display:none">
										<ul>
											<li class="level-1" data-bind="click:selectLevel.bind($data,1),css:{'current':level() == 1},text:arr[1]"></li>
											<li class="level-2" data-bind="click:selectLevel.bind($data,2),css:{'current':level() == 2},text:arr[2]"></li>
											<li class="level-3" data-bind="click:selectLevel.bind($data,3),css:{'current':level() == 3},text:arr[3]"></li>
										</ul>
									</div>
								</div>
								<button type="button" class="plan-btn plan-submit" data-bind="click:submit">创建</button>
								<button type="button" class="plan-btn plan-cancel" data-bind="click:function(){showTextarea(false)}">取消</button>
							</div>
							<div data-bind="if:plans().length > 0" class="card">
								<table class="plan-list" border="0" cellspacing="0">
									<thead>
										<tr>
											<th>内容</th>
											<th>状态</th>
										</tr>
									</thead>
									<tbody data-bind="foreach:plans">
										<tr>
											<td>
												<input class="magic-checkbox" type="checkbox" data-bind="attr:{id:'P'+$index()}">
												<label data-bind="text:text,attr:{for:'P'+$index()}"></label>
											</td>
												<td align="right" data-bind="text:$parent.arr[level]">
											</td>
										</tr>
									</tbody>
								</table>
								<div class="btn-group">
									<button type="button" class="btn btn-delete">删除</button>
									<button type="button" class="btn btn-complete">完成</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php echo $this->tpl('scripts');?>

<script type="text/javascript">
(function(a,c){

	var path = c.path;

	var viewModel = function(){

		this.showTextarea = ko.observable(false);
		this.textarea = ko.observable('');
		this.textareaFocus = ko.observable(false);
		this.plans = ko.observableArray([]);
		this.level = ko.observable(1);
		this.arr =  ['','普通','紧急','非常紧急'];
		this.priorityText = ko.computed(function(){
			return this.arr[this.level()];
		},this);
		this.optionsBox = ko.observable(false);

		this.changePriority = function(){
			this.optionsBox(true);
		}.bind(this);

		this.selectLevel = function(i){
			this.level(i);
			this.optionsBox(false);
		}

		this.create = function(){
			this.showTextarea(true);
			this.textareaFocus(true);
		}.bind(this);

		this.submit = function(){
			var _this = this;
			var text = this.textarea().trim(),
				level = this.level();

			if(text === ''){
				this.textareaFocus(true);
				return;
			}

			a.post(path+'admin/add/plan'
				,{
					text : text
					,level : level
				}
				,function(res){
					res = a.jsonParse(res);
					if(res.success){
						_this.plans.unshift({text:text,level:level});
						_this.showTextarea(false);
						_this.textarea('');
					}else{
						alert('创建失败!');
					}
				}
			);

		}.bind(this);
	}
	var vm = new viewModel;
	ko.applyBindings(vm,document.getElementById('plans'));

	a.get(path+'admin/plans',{},function(res){
		res = a.jsonParse(res);
		console.log(res)
		vm.plans(res.data);
	});

})(new Ajax,_CONFIG_);
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
})(new Ajax,_CONFIG_);
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
})(new Ajax,_CONFIG_);
</script>

<?php echo $this->tpl('end');?>