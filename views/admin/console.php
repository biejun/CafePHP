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
				<div class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<h3>登录日志<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
							<?php foreach ($loggedLogs as $row) :?>
							<ul class="item-list">
								<li>
									<span class="flag"><i class="icon-asterisk"></i></span>
									<span class="text">
										<strong class="mr-5"><?php echo $row['name'];?></strong>登录了管理后台，登录地点在<strong class="ml-5"><?php echo $row['city'];?></strong>。
										<time class="time mt-5"><?php echo date("Y-m-d H:i",$row['time']);?></time>
									</span>
								</li>
							</ul>
							<?php endforeach;?>
							<footer class="item-footer">
								<button class="ribbon-button">清理日志</button>
							</footer>
						</div>
					</div>
				</div>
				<div class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<h3>操作日志<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
							<?php foreach ($operateLogs as $row) :?>
							<ul class="item-list">
								<li>
									<span class="flag"><i class="icon-asterisk"></i></span>
									<span class="text">
										<strong class="mr-5"><?php echo $row['name'];?></strong><?php echo $row['text'];?>。
										<time class="time mt-5"><?php echo $row['time'];?></time>
									</span>
								</li>
							</ul>
							<?php endforeach;?>
							<footer class="item-footer">
								<button class="ribbon-button">清理日志</button>
							</footer>
						</div>
					</div>
				</div>
				<div id="plan" class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<h3>待办事项<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
							<div data-bind="visible:showTextarea,css:{'plan-textarea':true}" style="display:none">
								<textarea data-bind="value:planning" class="planning" rows="4" placeholder="任务内容"></textarea>
								<div class="plan-priority" data-bind="click:changePriority">
									<span class="priority" data-bind="text:priority"></span>
									优先级
									<div class="options-box" data-bind="visible:optionsBox" style="display:none">
										<ul>
											<li>ddd</li>
											<li>ddd</li>
										</ul>
									</div>
								</div>
								<button type="button" class="plan-btn plan-submit">创建</button>
								<button type="button" class="plan-btn plan-cancel" data-bind="click:function(){showTextarea(false)}">取消</button>
							</div>
							<footer class="item-footer" data-bind="visible:!showTextarea()">
								<button class="ribbon-button" data-bind="click:function(){showTextarea(true)},text:'新建任务'"></button>
							</footer>
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
	var Plan = function(text,){

	}
	var viewModel = function(){
		this.showTextarea = ko.observable(true);
		this.planning = ko.observable('');
		this.plans = ko.observableArray();
		this.priority = ko.observable('普通');
		this.optionsBox = ko.observable(false);
		this.changePriority = function(){
			this.optionsBox(true);
		}.bind(this);
	}

	ko.applyBindings(new viewModel,document.getElementById('plan'));
})(_CONFIG_);
</script>

<?php $this->show('end');?>