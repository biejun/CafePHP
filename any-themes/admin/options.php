<?php $ui->render('header');?>

<section id="container" class="content-page">
	
	<?php $ui->render('nav');?>
	<section class="page">
		<div class="mt-20">
			<?php include $file;?>
		</div>
	</section>
</section>

<?php $ui->render('footer');?>