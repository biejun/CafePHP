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
  
  <main class="page-main">
	  <div class="main-header-panel">
		<div class="header-panel__left">
			<h2>数据备份</h2>
		</div>
	  	<div class="header-panel__right">
			<form action="<?= $this->u('admin/console/backup/export');?>" method="post">
				<button type="submit" class="ui primary small button">备份数据库</button>
			</form>
	  	</div>
	  </div>
	  <table class="ui very basic small table">
	  	<thead>
	  		<tr>
	  			<th>编号</th>
	  			<th>文件名</th>
	  			<th>备份时间</th>
	  			<th>操作</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  		<?php foreach ($data as $row) :?>
	  		<tr>
	  			<td><?php echo $row['no'];?></td>
	  			<td><?php echo $row['file'];?></td>
	  			<td><?php echo $row['created'];?></td>
	  			<td>
	  				<form action="<?=$this->u('admin/console/backup/restore')?>"
	  					onsubmit="return check(this);"
	  					method="post">
	  					<input type="hidden" name="file" value="<?php echo $row['file'];?>">
	  					<button type="submit" class="ui button">还原</button>
	  				</form>
	  				<form action="<?= $this->u('admin/console/backup/delete');?>"
	  					onsubmit="return check(this);"
	  					method="post">
	  					<input type="hidden" name="file" value="<?php echo $row['file'];?>">
	  					<button type="submit" class="ui button">删除</button>
	  				</form>
	  			</td>
	  		</tr>
	  		<?php endforeach;?>
	  	</tbody>
	  </table>
  </main>
</section>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/aside-nav.js')
    ->js('/admin/js/index.js', '1.0.12');
?>

<script>
function check(o){
	if(confirm('确定要操作吗？')){
		return true;
	}else{
		return false;
	}
}
</script>

<?php $this->stop() ?>