
<?php if(isset($this->assets['js'])) : ?>
<?php foreach( $this->assets['js'] as $jsPath ) :?>
	<script type="text/javascript" src="<?=$jsPath;?>"></script>
<?php endforeach;?>
<?php endif;?>