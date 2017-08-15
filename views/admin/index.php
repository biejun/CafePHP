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
							<?php foreach ($login_logs as $row) :?>
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
						</div>
					</div>
				</div>
				<div class="width-3-1">
					<div class="item-box">
						<header class="item-header">
							<h3>待办事项<i class="icon icon-angle-down"></i></h3>
						</header>
						<div class="item-body">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<?php $this->show('end');?>