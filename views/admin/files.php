<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="right-ribbons">
				<form action="<?php echo $this->path;?>admin/post/backup/export" method="post">
					<button type="submit" class="ribbon-button" role="button"><?php echo $buttonText;?>(<?php echo $totalSize;?>)</button>
				</form>
			</div>
			<h2><?php echo $subtitle;?></h2>
		</div>
		<div class="table">
			<div class="thead">
				<div class="tr">
					<div class="th">编号</div>
					<div class="th">文件名</div>
					<div class="th">大小</div>
					<div class="th">创建时间</div>
					<div class="th">操作</div>
				</div>
			</div>
			<div class="tbody">
				<?php foreach ($data as $row) :?>
				<div class="tr">
					<div class="td" data-label="编号"><?php echo $row['no'];?></div>
					<div class="td" data-label="文件名"><?php echo $row['file'];?></div>
					<div class="td" data-label="大小"><?php echo $row['size'];?></div>
					<div class="td" data-label="创建时间"><?php echo $row['created'];?></div>
					<div class="td" data-label="操作">
<!-- 						<form action="<?php echo $this->path;?>admin/post/backup/restore"
							onsubmit="return check(this);" class="table-btn-group"
							method="post">
							<input type="hidden" name="file" value="<?php echo $row['file'];?>">
							<button type="submit">还原</button>
						</form>
						<form action="<?php echo $this->path;?>admin/post/backup/delete"
							onsubmit="return check(this);" class="table-btn-group"
							method="post">
							<input type="hidden" name="file" value="<?php echo $row['file'];?>">
							<button type="submit">删除</button>
						</form> -->
					</div>
				</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<?php $this->show('end');?>