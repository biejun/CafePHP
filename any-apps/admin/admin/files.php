<?php
if(!defined('ABSPATH'))exit('Access denied!');

$do = get_query_var('do');
$path = get_page_url();

$array = array();
$temp_dir = ANYINC.'cache/temp/';
make_dir($temp_dir);
if($handle=opendir($temp_dir)){
	$no=1;
	while(false!==($dir=readdir($handle))){
		if ($dir == '.'||$dir=='..'||$dir=='index.htm')continue;
		$array[$no]['no']=$no;
		$array[$no]['filename']=$dir;
		$array[$no]['filepath']=str_replace(ABSPATH,PATH,$temp_dir).$dir;
		$array[$no]['delete']=$path.'&do=delete_file&filename='.$dir;
		$array[$no]['lasttime']=date('Y-m-d H:i:s',filemtime($temp_dir.$dir));
		$no++;
	}
	closedir($handle);
}
if(isset($do)){
	if($do=='clear_all'){
		if(!empty($array)){
			foreach ($array as $key => $value) {
				@unlink($temp_dir.$value['filename']);
			}
			$ui->alert('临时文件已清空!');
		}else{
			$ui->alert('未找到临时文件!');
		}
	}elseif($do=='delete_file'){
		$filename = $_GET['filename'];
		if(!empty($filename)){
			@unlink($temp_dir.$filename);
			$ui->alert('删除成功!');
		}
	}
}
?>
<div id="app" class="panel ml-15 mr-15 options">
	<header class="panel-heading">
		<h3>清空临时文件</h3>
	</header>
	<div class="panel-body search">
		<span class="fr">
			<a href="<?php echo $path;?>&do=clear_all" class="btn btn-primary">清空所有临时文件</a>
		</span>
		<h3 class="title">文件列表</h3>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>文件</th>
				<th>上传时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="row in files" v-cloak>
				<td>
					<a v-bind:href="row.filepath" v-text="row.filename"></a>
				</td>
				<td v-text="row.lasttime"></td>
				<td>
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
			files : <?php echo json_encode($array);?>
		}
	});	
</script>
