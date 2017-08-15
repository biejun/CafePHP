<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<h2><?php echo $subtitle;?></h2>
		</div>
		<div class="row">
			<div class="width-6-1">
				<ul id="tabNav" class="tab-nav" data-bind="foreach:tabNav">
					<li>
						<a data-bind="css:{'current':$parent.tabCurrent == name},attr:{href:url},text:title"></a>
					</li>
				</ul>
			</div>
			<div class="width-6-5">
				<div class="tab-body">
					<?php if($do == 'edit'): ?>
					<form class="settings">
						<table class="s-table" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<th width="220" align="right">
										账号
									</th>
									<td width="500">
										<?=$admin_name;?>
									</td>
								</tr>
								<tr>
									<th width="220" align="right">
										一句话介绍
									</th>
									<td width="500">
										<input type="text" class="s-input"/>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td>
										<button type="submit" class="s-button mt-10">提交</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</form>
					<?php elseif($do == 'password'): ?>
					<form class="settings" action="<?php echo $this->path;?>admin/update/password" method="post" onsubmit="return checkPassword(this);">
						<table class="s-table" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<th width="220" align="right">
										当前密码
									</th>
									<td width="500">
										<input type="password" name="oldPassword" class="s-input"/>
									</td>
								</tr>
								<tr>
									<th width="220" align="right">
										新的密码
									</th>
									<td width="500">
										<input type="password" name="newPassword" class="s-input"/>
									</td>
								</tr>
								<tr>
									<th width="220" align="right">
										确认密码
									</th>
									<td width="500">
										<input type="password" name="newPasswordOnce" class="s-input"/>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td>
										<button type="submit" class="s-button mt-10">提交</button>
									</td>
								</tr>
							</tfoot>
						</table>
					</form>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<script>
(function(r){

	var u = new r();

	var tabNav = function(tabTitle,tabName){
		this.title = tabTitle;
		this.name = tabName;
		this.url = u.path + '?do=' + tabName;
	}

	var viewModel = function(){
		this.tabCurrent = u.getQuery('do');
		this.tabNav = ko.observableArray([
			new tabNav('账号信息','edit')
			,new tabNav('修改密码','password')
		]);
	};

	ko.applyBindings(new viewModel,document.getElementById('tabNav'));
})(Request);
</script>
<script>
function notifyMsg(type,msg){
	notify.notifyToShow(true);
	notify.type(type);
	notify.msg(msg);
}
function checkPassword(o){
	if(o.oldPassword.value === ''){
		notifyMsg('error','请输入当前密码!');
		o.oldPassword.focus();
		return false;
	}
	if(o.newPassword.value === '' || o.newPassword.value.length < 6){
		notifyMsg('error','请输入不少于6位数的新密码!');
		o.newPassword.focus();
		return false;
	}
	return true;
}
</script>

<?php $this->show('end');?>