<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<h2><?php echo $subtitle;?></h2>
		</div>
		<form class="settings" action="<?php echo $this->path;?>admin/update/password" method="post">
			<table class="s-table" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<th width="220" align="right">
							旧密码
						</th>
						<td width="500">
							<input type="password" name="oldPassword" class="s-input"/>
						</td>
					</tr>
					<tr>
						<th width="220" align="right">
							新密码
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
	</div>
</section>

<?php $this->show('scripts');?>

<?php $this->show('end');?>