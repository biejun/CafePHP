<?php
if(!defined('ABSPATH'))exit('Access denied!');

$do = get_query_var('do');
$path = get_page_url();

$array=array();
$backup_dir = ANYINC.'cache/backup/';
make_dir($backup_dir);
if($handle=opendir($backup_dir)){
	$no=1;
	while(false!==($dir=readdir($handle))){
		if (strpos($dir,'.sql')!==false){
			$array[$no]['no']=$no;
			$array[$no]['filename']=$dir;
			$array[$no]['restore']=$path.'&do=backup_restore&filename='.$dir;
			$array[$no]['delete']=$path.'&do=backup_delete&filename='.$dir;
			$array[$no]['lasttime']=date('Y-m-d H:i:s',filemtime($backup_dir.$dir));
			$no++;
		}
	}
	closedir($handle);
}

if(isset($do)){
	if($do=='backup'){
		$content=Widget::get('admin')->export();
		$date=date('Ymd');
		$filename=md5($date.mt_rand(0,99999));
		$filename=$date."_".substr($filename,0,10).".sql";
		file_put_contents($backup_dir.$filename,$content);
		$ui->alert('创建数据备份['.$filename.']');
	}elseif($do=='backup_restore'){
		if(isset($_GET['filename'])){
			$filename=trim($_GET['filename']);
			$filename=$backup_dir.$filename;
			if(file_exists($filename)){
				$content=file_get_contents($filename);
				$line=explode(";\n",$content);
				widget('admin')->query($line);
			}
			$ui->alert('恢复数据备份['.$filename.']');
		}
	}elseif($do=='backup_delete'){
		if(isset($_GET['filename'])){
			$filename=trim($_GET['filename']);
			@unlink($backup_dir.$filename);
			$ui->alert('删除数据备份['.$filename.']');
		}
	}
}
?>

<div id="app" class="panel ml-15 mr-15 options">
	<header class="panel-heading">
		<h3>数据库备份/恢复</h3>
	</header>
	<div class="panel-body search">
		<span class="fr">
			<a href="<?php echo $path;?>&do=backup" class="btn btn-primary">新增备份</a>
		</span>
		<h3 class="title">备份文件在<?php echo str_replace(ABSPATH,PATH, $backup_dir);?>目录下，备份前请先确定文件目录属性是否为(0777)可写状态</h3>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>文件名</th>
				<th>生成时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="row in files" v-cloak>
				<td v-text="row.filename"></td>
				<td v-text="row.lasttime"></td>
				<td>
					<a v-bind:href="row.restore">恢复</a>
					<a v-bind:href="row.delete">删除</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	new Vue({
		el : "#app",
		data : {
			files : <?php echo json_encode($array); ?>
		}
	});
</script>