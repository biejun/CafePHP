<?php
$this->minifyJS([
	SOURCES.'/js/jquery-3.5.1.min.js',
	SOURCES.'/js/semantic.min.js',
	SOURCES.'/js/knockout-3.4.2.js',
	SOURCES.'/js/md5.js',
	SOURCES.'/js/vendor.js'
], 'v1/js/chunk-common.js', '1.0.0');
?>