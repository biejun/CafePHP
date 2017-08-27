<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="fr mt-5">
				<button type="submit" class="ribbon-button" role="button"><i class="icon icon-plus-circled"></i>自定义配置项</button>
			</div>
			<h2><?php echo $subtitle;?></h2>
		</div>
		<form class="settings" action="<?php echo $this->path;?>admin/update/setting" method="post">
			<table class="s-table" cellspacing="0" cellpadding="0">
				<tbody>
					<?php foreach ($data as $row) :?>
					<tr>
						<th width="220" align="right">
							<?php echo $row['alias'];?>
						</th>
						<td width="120">
							<span class="item-name">(<?php echo $row['name'];?>)</span>
						</td>
						<td width="500" class="form-group">
							<?php if($row['type'] === 'text') : ?>
								<input name="<?php echo $row['name'];?>[value]" class="form-control" value="<?php echo $row['value'];?>">
							<?php elseif($row['type'] === 'bigtext') : ?>
								<textarea rows="4" name="<?php echo $row['name'];?>[value]" class="form-control"><?php echo $row['value'];?></textarea>
							<?php endif;?>
						</td>
					</tr>
					<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
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