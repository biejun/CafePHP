<?php echo $this->tpl('start');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="fr mt-5">
				<form action="<?php echo $this->path;?>admin/console/backup/export" method="post">
					<button type="submit" class="ribbon-button" role="button"><i class="icon icon-plus-circled"></i>备份数据库</button>
				</form>
			</div>
			<h2>数据备份</h2>
		</div>
		<div class="table">
			<div class="thead">
				<div class="tr">
					<div class="th">编号</div>
					<div class="th">文件名</div>
					<div class="th">备份时间</div>
					<div class="th">操作</div>
				</div>
			</div>
			<div class="tbody">
				<?php foreach ($data as $row) :?>
				<div class="tr">
					<div class="td" data-label="编号"><?php echo $row['no'];?></div>
					<div class="td" data-label="文件名"><?php echo $row['file'];?></div>
					<div class="td" data-label="备份时间"><?php echo $row['created'];?></div>
					<div class="td" data-label="操作">
						<form action="<?php echo $this->path;?>admin/console/backup/restore"
							onsubmit="return check(this);" class="table-btn-group"
							method="post">
							<input type="hidden" name="file" value="<?php echo $row['file'];?>">
							<button type="submit">还原</button>
						</form>
						<form action="<?php echo $this->path;?>admin/console/backup/delete"
							onsubmit="return check(this);" class="table-btn-group"
							method="post">
							<input type="hidden" name="file" value="<?php echo $row['file'];?>">
							<button type="submit">删除</button>
						</form>
					</div>
				</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</section>

<?php echo $this->tpl('scripts');?>
<script>
function check(o){
	if(confirm('确定要操作吗？')){
		return true;
	}else{
		return false;
	}
}
</script>

<?php echo $this->tpl('end');?>