<?php
if(!defined('ABSPATH'))exit('Access denied!');

$cache = Core::$cache;
$do = get_query_var('do');
$path = get_page_url();

$cache_files = array();
$cache_total_size = 0;
$files = $cache->getCacheFiles();

foreach ($files as $key => $file) {
	$cache_files[$key]['time'] = date("Y-m-d H:i:s",filemtime($file));
	$filesize = filesize($file);
	$cache_total_size +=$filesize;
	$cache_files[$key]['size'] = UIKit::formatSize($filesize);
	$cache_files[$key]['path'] = str_replace(ABSPATH,PATH, $file);
}

if(isset($do)&&$do=='clear_cache'){
	if($cache->clearCaches()){
		$ui->alert('清理成功!');
	}else{
		$ui->alert('清理失败!');
	}
}
?>

<div id="app" class="panel ml-15 mr-15 options">
	<header class="panel-heading">
		<h3>系统缓存</h3>
	</header>
	<div class="panel-body search">
		<span class="fr">
			<a href="<?php echo $path;?>&do=clear_cache" class="btn btn-primary">清空全部缓存 ( {{totalSize}} )</a>
		</span>
		<h3 class="title">缓存文件列表（ <span v-cloak>{{files.length}}</span> ）</h3>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>缓存文件</th>
				<th>生成时间</th>
				<th>大小</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="row in files" v-cloak>
				<td v-text="row.path"></td>
				<td v-text="row.time"></td>
				<td v-text="row.size"></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	new Vue({
		el : "#app",
		data : {
			files : <?php echo json_encode($cache_files);?>,
			totalSize : "<?php echo UIKit::formatSize($cache_total_size);?>"
		}
	});
</script>