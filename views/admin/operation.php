<?php $this->show('start');?>

<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="right-ribbons">
			</div>
			<h2><?php echo $subtitle;?></h2>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<script>
(function(c){
	var path = c.path;

	ajax.post(path+'admin/api/users/getusers'
		,{
			page : 1
			,limit: 10
		}
		,function(res){
			res = ajax.jsonParse(res);

			if(res.status){

			}else{
				
			}
			console.log(res);
		}
	);

})(_CONFIG_)
</script>

<?php $this->show('end');?>