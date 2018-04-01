<?php if(isset($this->assets['js'])) : ?>
<?php foreach( $this->assets['js'] as $jsPath ) :?>
	<script type="text/javascript" src="<?=$jsPath;?>"></script>
<?php endforeach;?>
<?php endif;?>


<script>
	
	var a = new Ajax;

	a.url('/admin/api/loginlogs')
	 .data({page:1,limit:10})
	 .post(function(res) {
	 	console.log(res)
	 })
</script>

</body>
</html>