<?php
$this->minifyJS([
	ASSETS.'/js/jquery-3.5.1.min.js',
	ASSETS.'/js/semantic.min.js',
	ASSETS.'/js/knockout-3.4.2.js',
	ASSETS.'/js/md5.js',
	ASSETS.'/js/vendor.js'
], 'v1/js/chunk-common.js', '1.0.0');
?>