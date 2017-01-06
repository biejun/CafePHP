<?php $ui->render('header');?>

<section id="container" class="content-page">

	<?php $ui->render('nav');?>

	<section id="app" class="page">
		<div class="mt-20">
			<div class="tabs ml-15 mr-15">
				<a href="javascript:;" v-bind:class="{current:tabBody=='setting'}" @click="tabBody ='setting'">系统与应用</a>
				<a href="javascript:;" v-bind:class="{current:tabBody=='theme'}" @click="tabBody ='theme'">主题与外观</a>
			</div>
			<div class="row" v-if="tabBody=='setting'">
				<?php Action::on('admin:setting');?>
			</div>
			<div v-if="tabBody=='theme'">
				<div class="panel ml-15 mr-15">
					<header class="panel-heading">
						<h3>主题外观设置</h3>
					</header>
					<table class="table">
						<thead>
							<tr>
								<th>主题</th>
								<th>描述</th>
								<th>作者</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
						<?php if($themes) : ?>
							<?php foreach( $themes as $row) : ?>
							<tr>
								<td><?php echo $row['name'];?></td>
								<td class="text-muted"><?php echo $row['description'];?></td>
								<td><?php echo $row['author'];?></td>
								<td>
									<?php if($row['actived']) : ?>
									<a href="">已使用</a>
									<?php endif;?>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</section>

<script type="text/javascript">
	new Vue({
		el : '#app',
		data : {
			path : '<?php echo $ui->path;?>',
			tabBody : 'setting'
		}
	});
</script>

<?php $ui->render('footer');?>