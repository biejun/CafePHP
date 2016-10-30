<?php
if(!defined('ABSPATH'))exit('Access denied!');

$options = array(
	'title' => '字体图标',
	'template' => '
		<style type="text/css">
		.the-icons {
		  font-size: 14px;
		  line-height: 24px;
		}
		.codesOn .i-name {
		  display: none;
		}
		.codesOn .i-code {
		  display: inline;
		}
		.i-code {
		  display: none;
		} 
		.demo-icon
		{
		  font-family: "fontello";
		  font-style: normal;
		  font-weight: normal;
		  speak: none;
		 
		  display: inline-block;
		  text-decoration: inherit;
		  width: 1em;
		  margin-right: .2em;
		  text-align: center;
		  /* opacity: .8; */
		 
		  /* For safety - reset parent styles, that can break glyph codes*/
		  font-variant: normal;
		  text-transform: none;
		 
		  /* fix buttons height, for twitter bootstrap */
		  line-height: 1em;
		 
		  /* Animation center compensation - margins should be symmetric */
		  /* remove if not needed */
		  margin-left: .2em;
		 
		  /* You can be more comfortable with increased icons size */
		  /* font-size: 120%; */
		 
		  /* Font smoothing. That was taken from TWBS */
		  -webkit-font-smoothing: antialiased;
		  -moz-osx-font-smoothing: grayscale;
		 
		  /* Uncomment for 3D effect */
		  /* text-shadow: 1px 1px 1px rgba(127, 127, 127, 0.3); */
		}
		</style>
		<div id="app" class="panel ml-15 mr-15 options">
			<header class="panel-heading">
				<h3>字体图标</h3>
			</header>
			<div class="panel-body">
			  <div class="row">
			    <div title="Code: 0xe800" class="width-4-1"><i class="demo-icon emo-happy">&#xe800;</i> <span class="i-name">icon-emo-happy</span><span class="i-code">0xe800</span></div>
			    <div title="Code: 0xe801" class="width-4-1"><i class="demo-icon emo-unhappy">&#xe801;</i> <span class="i-name">icon-emo-unhappy</span><span class="i-code">0xe801</span></div>
			    <div title="Code: 0xe802" class="width-4-1"><i class="demo-icon music">&#xe802;</i> <span class="i-name">icon-music</span><span class="i-code">0xe802</span></div>
			    <div title="Code: 0xe803" class="width-4-1"><i class="demo-icon search">&#xe803;</i> <span class="i-name">icon-search</span><span class="i-code">0xe803</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe804" class="width-4-1"><i class="demo-icon mail">&#xe804;</i> <span class="i-name">icon-mail</span><span class="i-code">0xe804</span></div>
			    <div title="Code: 0xe805" class="width-4-1"><i class="demo-icon mail-alt">&#xe805;</i> <span class="i-name">icon-mail-alt</span><span class="i-code">0xe805</span></div>
			    <div title="Code: 0xe806" class="width-4-1"><i class="demo-icon heart">&#xe806;</i> <span class="i-name">icon-heart</span><span class="i-code">0xe806</span></div>
			    <div title="Code: 0xe807" class="width-4-1"><i class="demo-icon heart-empty">&#xe807;</i> <span class="i-name">icon-heart-empty</span><span class="i-code">0xe807</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe808" class="width-4-1"><i class="demo-icon star">&#xe808;</i> <span class="i-name">icon-star</span><span class="i-code">0xe808</span></div>
			    <div title="Code: 0xe809" class="width-4-1"><i class="demo-icon star-empty">&#xe809;</i> <span class="i-name">icon-star-empty</span><span class="i-code">0xe809</span></div>
			    <div title="Code: 0xe80a" class="width-4-1"><i class="demo-icon star-half">&#xe80a;</i> <span class="i-name">icon-star-half</span><span class="i-code">0xe80a</span></div>
			    <div title="Code: 0xe80b" class="width-4-1"><i class="demo-icon star-half-alt">&#xe80b;</i> <span class="i-name">icon-star-half-alt</span><span class="i-code">0xe80b</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe80c" class="width-4-1"><i class="demo-icon user">&#xe80c;</i> <span class="i-name">icon-user</span><span class="i-code">0xe80c</span></div>
			    <div title="Code: 0xe80d" class="width-4-1"><i class="demo-icon male">&#xe80d;</i> <span class="i-name">icon-male</span><span class="i-code">0xe80d</span></div>
			    <div title="Code: 0xe80e" class="width-4-1"><i class="demo-icon videocam">&#xe80e;</i> <span class="i-name">icon-videocam</span><span class="i-code">0xe80e</span></div>
			    <div title="Code: 0xe80f" class="width-4-1"><i class="demo-icon picture">&#xe80f;</i> <span class="i-name">icon-picture</span><span class="i-code">0xe80f</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe810" class="width-4-1"><i class="demo-icon camera">&#xe810;</i> <span class="i-name">icon-camera</span><span class="i-code">0xe810</span></div>
			    <div title="Code: 0xe811" class="width-4-1"><i class="demo-icon th-large">&#xe811;</i> <span class="i-name">icon-th-large</span><span class="i-code">0xe811</span></div>
			    <div title="Code: 0xe812" class="width-4-1"><i class="demo-icon th">&#xe812;</i> <span class="i-name">icon-th</span><span class="i-code">0xe812</span></div>
			    <div title="Code: 0xe813" class="width-4-1"><i class="demo-icon ok">&#xe813;</i> <span class="i-name">icon-ok</span><span class="i-code">0xe813</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe814" class="width-4-1"><i class="demo-icon ok-circled">&#xe814;</i> <span class="i-name">icon-ok-circled</span><span class="i-code">0xe814</span></div>
			    <div title="Code: 0xe815" class="width-4-1"><i class="demo-icon plus">&#xe815;</i> <span class="i-name">icon-plus</span><span class="i-code">0xe815</span></div>
			    <div title="Code: 0xe816" class="width-4-1"><i class="demo-icon minus">&#xe816;</i> <span class="i-name">icon-minus</span><span class="i-code">0xe816</span></div>
			    <div title="Code: 0xe817" class="width-4-1"><i class="demo-icon minus-circled">&#xe817;</i> <span class="i-name">icon-minus-circled</span><span class="i-code">0xe817</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe818" class="width-4-1"><i class="demo-icon plus-circled">&#xe818;</i> <span class="i-name">icon-plus-circled</span><span class="i-code">0xe818</span></div>
			    <div title="Code: 0xe819" class="width-4-1"><i class="demo-icon minus-squared">&#xe819;</i> <span class="i-name">icon-minus-squared</span><span class="i-code">0xe819</span></div>
			    <div title="Code: 0xe81a" class="width-4-1"><i class="demo-icon minus-squared-alt">&#xe81a;</i> <span class="i-name">icon-minus-squared-alt</span><span class="i-code">0xe81a</span></div>
			    <div title="Code: 0xe81b" class="width-4-1"><i class="demo-icon flag">&#xe81b;</i> <span class="i-name">icon-flag</span><span class="i-code">0xe81b</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe81c" class="width-4-1"><i class="demo-icon attention">&#xe81c;</i> <span class="i-name">icon-attention</span><span class="i-code">0xe81c</span></div>
			    <div title="Code: 0xe81d" class="width-4-1"><i class="demo-icon tags">&#xe81d;</i> <span class="i-name">icon-tags</span><span class="i-code">0xe81d</span></div>
			    <div title="Code: 0xe81e" class="width-4-1"><i class="demo-icon left-open">&#xe81e;</i> <span class="i-name">icon-left-open</span><span class="i-code">0xe81e</span></div>
			    <div title="Code: 0xe81f" class="width-4-1"><i class="demo-icon eye">&#xe81f;</i> <span class="i-name">icon-eye</span><span class="i-code">0xe81f</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe820" class="width-4-1"><i class="demo-icon pin">&#xe820;</i> <span class="i-name">icon-pin</span><span class="i-code">0xe820</span></div>
			    <div title="Code: 0xe821" class="width-4-1"><i class="demo-icon right-open">&#xe821;</i> <span class="i-name">icon-right-open</span><span class="i-code">0xe821</span></div>
			    <div title="Code: 0xe822" class="width-4-1"><i class="demo-icon lock-open">&#xe822;</i> <span class="i-name">icon-lock-open</span><span class="i-code">0xe822</span></div>
			    <div title="Code: 0xe823" class="width-4-1"><i class="demo-icon lock">&#xe823;</i> <span class="i-name">icon-lock</span><span class="i-code">0xe823</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe824" class="width-4-1"><i class="demo-icon link">&#xe824;</i> <span class="i-name">icon-link</span><span class="i-code">0xe824</span></div>
			    <div title="Code: 0xe825" class="width-4-1"><i class="demo-icon info-circled">&#xe825;</i> <span class="i-name">icon-info-circled</span><span class="i-code">0xe825</span></div>
			    <div title="Code: 0xe826" class="width-4-1"><i class="demo-icon help-circled">&#xe826;</i> <span class="i-name">icon-help-circled</span><span class="i-code">0xe826</span></div>
			    <div title="Code: 0xe827" class="width-4-1"><i class="demo-icon tablet">&#xe827;</i> <span class="i-name">icon-tablet</span><span class="i-code">0xe827</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe828" class="width-4-1"><i class="demo-icon mobile">&#xe828;</i> <span class="i-name">icon-mobile</span><span class="i-code">0xe828</span></div>
			    <div title="Code: 0xe829" class="width-4-1"><i class="demo-icon award">&#xe829;</i> <span class="i-name">icon-award</span><span class="i-code">0xe829</span></div>
			    <div title="Code: 0xe82a" class="width-4-1"><i class="demo-icon female">&#xe82a;</i> <span class="i-name">icon-female</span><span class="i-code">0xe82a</span></div>
			    <div title="Code: 0xe82b" class="width-4-1"><i class="demo-icon thumbs-up-alt">&#xe82b;</i> <span class="i-name">icon-thumbs-up-alt</span><span class="i-code">0xe82b</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe82c" class="width-4-1"><i class="demo-icon thumbs-down-alt">&#xe82c;</i> <span class="i-name">icon-thumbs-down-alt</span><span class="i-code">0xe82c</span></div>
			    <div title="Code: 0xe82d" class="width-4-1"><i class="demo-icon download-cloud">&#xe82d;</i> <span class="i-name">icon-download-cloud</span><span class="i-code">0xe82d</span></div>
			    <div title="Code: 0xe82e" class="width-4-1"><i class="demo-icon quote-left">&#xe82e;</i> <span class="i-name">icon-quote-left</span><span class="i-code">0xe82e</span></div>
			    <div title="Code: 0xe82f" class="width-4-1"><i class="demo-icon quote-right">&#xe82f;</i> <span class="i-name">icon-quote-right</span><span class="i-code">0xe82f</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe830" class="width-4-1"><i class="demo-icon code">&#xe830;</i> <span class="i-name">icon-code</span><span class="i-code">0xe830</span></div>
			    <div title="Code: 0xe831" class="width-4-1"><i class="demo-icon print">&#xe831;</i> <span class="i-name">icon-print</span><span class="i-code">0xe831</span></div>
			    <div title="Code: 0xe832" class="width-4-1"><i class="demo-icon pencil">&#xe832;</i> <span class="i-name">icon-pencil</span><span class="i-code">0xe832</span></div>
			    <div title="Code: 0xe833" class="width-4-1"><i class="demo-icon bell-alt">&#xe833;</i> <span class="i-name">icon-bell-alt</span><span class="i-code">0xe833</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe834" class="width-4-1"><i class="demo-icon doc-inv">&#xe834;</i> <span class="i-name">icon-doc-inv</span><span class="i-code">0xe834</span></div>
			    <div title="Code: 0xe835" class="width-4-1"><i class="demo-icon bell-off">&#xe835;</i> <span class="i-name">icon-bell-off</span><span class="i-code">0xe835</span></div>
			    <div title="Code: 0xe836" class="width-4-1"><i class="demo-icon down-open">&#xe836;</i> <span class="i-name">icon-down-open</span><span class="i-code">0xe836</span></div>
			    <div title="Code: 0xe837" class="width-4-1"><i class="demo-icon up-open">&#xe837;</i> <span class="i-name">icon-up-open</span><span class="i-code">0xe837</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe839" class="width-4-1"><i class="demo-icon attention-circled">&#xe839;</i> <span class="i-name">icon-attention-circled</span><span class="i-code">0xe839</span></div>
			    <div title="Code: 0xe83a" class="width-4-1"><i class="demo-icon location">&#xe83a;</i> <span class="i-name">icon-location</span><span class="i-code">0xe83a</span></div>
			    <div title="Code: 0xe83b" class="width-4-1"><i class="demo-icon direction">&#xe83b;</i> <span class="i-name">icon-direction</span><span class="i-code">0xe83b</span></div>
			    <div title="Code: 0xe83c" class="width-4-1"><i class="demo-icon youtube-play">&#xe83c;</i> <span class="i-name">icon-youtube-play</span><span class="i-code">0xe83c</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe83d" class="width-4-1"><i class="demo-icon ok-1">&#xe83d;</i> <span class="i-name">icon-ok-1</span><span class="i-code">0xe83d</span></div>
			    <div title="Code: 0xe83e" class="width-4-1"><i class="demo-icon doc">&#xe83e;</i> <span class="i-name">icon-doc</span><span class="i-code">0xe83e</span></div>
			    <div title="Code: 0xe83f" class="width-4-1"><i class="demo-icon docs">&#xe83f;</i> <span class="i-name">icon-docs</span><span class="i-code">0xe83f</span></div>
			    <div title="Code: 0xe843" class="width-4-1"><i class="demo-icon file-pdf">&#xe843;</i> <span class="i-name">icon-file-pdf</span><span class="i-code">0xe843</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe844" class="width-4-1"><i class="demo-icon file-word">&#xe844;</i> <span class="i-name">icon-file-word</span><span class="i-code">0xe844</span></div>
			    <div title="Code: 0xe845" class="width-4-1"><i class="demo-icon file-excel">&#xe845;</i> <span class="i-name">icon-file-excel</span><span class="i-code">0xe845</span></div>
			    <div title="Code: 0xe846" class="width-4-1"><i class="demo-icon file-powerpoint">&#xe846;</i> <span class="i-name">icon-file-powerpoint</span><span class="i-code">0xe846</span></div>
			    <div title="Code: 0xe847" class="width-4-1"><i class="demo-icon file-image">&#xe847;</i> <span class="i-name">icon-file-image</span><span class="i-code">0xe847</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe848" class="width-4-1"><i class="demo-icon cancel">&#xe848;</i> <span class="i-name">icon-cancel</span><span class="i-code">0xe848</span></div>
			    <div title="Code: 0xe84b" class="width-4-1"><i class="demo-icon file-code">&#xe84b;</i> <span class="i-name">icon-file-code</span><span class="i-code">0xe84b</span></div>
			    <div title="Code: 0xe84c" class="width-4-1"><i class="demo-icon folder">&#xe84c;</i> <span class="i-name">icon-folder</span><span class="i-code">0xe84c</span></div>
			    <div title="Code: 0xe84d" class="width-4-1"><i class="demo-icon folder-open">&#xe84d;</i> <span class="i-name">icon-folder-open</span><span class="i-code">0xe84d</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe84e" class="width-4-1"><i class="demo-icon folder-empty">&#xe84e;</i> <span class="i-name">icon-folder-empty</span><span class="i-code">0xe84e</span></div>
			    <div title="Code: 0xe84f" class="width-4-1"><i class="demo-icon folder-open-empty">&#xe84f;</i> <span class="i-name">icon-folder-open-empty</span><span class="i-code">0xe84f</span></div>
			    <div title="Code: 0xe851" class="width-4-1"><i class="demo-icon phone">&#xe851;</i> <span class="i-name">icon-phone</span><span class="i-code">0xe851</span></div>
			    <div title="Code: 0xe852" class="width-4-1"><i class="demo-icon menu">&#xe852;</i> <span class="i-name">icon-menu</span><span class="i-code">0xe852</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe853" class="width-4-1"><i class="demo-icon cog">&#xe853;</i> <span class="i-name">icon-cog</span><span class="i-code">0xe853</span></div>
			    <div title="Code: 0xe854" class="width-4-1"><i class="demo-icon wrench">&#xe854;</i> <span class="i-name">icon-wrench</span><span class="i-code">0xe854</span></div>
			    <div title="Code: 0xe855" class="width-4-1"><i class="demo-icon basket">&#xe855;</i> <span class="i-name">icon-basket</span><span class="i-code">0xe855</span></div>
			    <div title="Code: 0xe856" class="width-4-1"><i class="demo-icon calendar">&#xe856;</i> <span class="i-name">icon-calendar</span><span class="i-code">0xe856</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe857" class="width-4-1"><i class="demo-icon volume-up">&#xe857;</i> <span class="i-name">icon-volume-up</span><span class="i-code">0xe857</span></div>
			    <div title="Code: 0xe858" class="width-4-1"><i class="demo-icon volume-down">&#xe858;</i> <span class="i-name">icon-volume-down</span><span class="i-code">0xe858</span></div>
			    <div title="Code: 0xe859" class="width-4-1"><i class="demo-icon volume-off">&#xe859;</i> <span class="i-name">icon-volume-off</span><span class="i-code">0xe859</span></div>
			    <div title="Code: 0xe85a" class="width-4-1"><i class="demo-icon clock">&#xe85a;</i> <span class="i-name">icon-clock</span><span class="i-code">0xe85a</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe85b" class="width-4-1"><i class="demo-icon resize-full">&#xe85b;</i> <span class="i-name">icon-resize-full</span><span class="i-code">0xe85b</span></div>
			    <div title="Code: 0xe85c" class="width-4-1"><i class="demo-icon resize-full-alt">&#xe85c;</i> <span class="i-name">icon-resize-full-alt</span><span class="i-code">0xe85c</span></div>
			    <div title="Code: 0xe85d" class="width-4-1"><i class="demo-icon resize-small">&#xe85d;</i> <span class="i-name">icon-resize-small</span><span class="i-code">0xe85d</span></div>
			    <div title="Code: 0xe85e" class="width-4-1"><i class="demo-icon zoom-in">&#xe85e;</i> <span class="i-name">icon-zoom-in</span><span class="i-code">0xe85e</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe85f" class="width-4-1"><i class="demo-icon zoom-out">&#xe85f;</i> <span class="i-name">icon-zoom-out</span><span class="i-code">0xe85f</span></div>
			    <div title="Code: 0xe860" class="width-4-1"><i class="demo-icon down-dir">&#xe860;</i> <span class="i-name">icon-down-dir</span><span class="i-code">0xe860</span></div>
			    <div title="Code: 0xe861" class="width-4-1"><i class="demo-icon up-dir">&#xe861;</i> <span class="i-name">icon-up-dir</span><span class="i-code">0xe861</span></div>
			    <div title="Code: 0xe863" class="width-4-1"><i class="demo-icon right-dir">&#xe863;</i> <span class="i-name">icon-right-dir</span><span class="i-code">0xe863</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe864" class="width-4-1"><i class="demo-icon angle-left">&#xe864;</i> <span class="i-name">icon-angle-left</span><span class="i-code">0xe864</span></div>
			    <div title="Code: 0xe865" class="width-4-1"><i class="demo-icon angle-right">&#xe865;</i> <span class="i-name">icon-angle-right</span><span class="i-code">0xe865</span></div>
			    <div title="Code: 0xe866" class="width-4-1"><i class="demo-icon angle-up">&#xe866;</i> <span class="i-name">icon-angle-up</span><span class="i-code">0xe866</span></div>
			    <div title="Code: 0xe867" class="width-4-1"><i class="demo-icon angle-down">&#xe867;</i> <span class="i-name">icon-angle-down</span><span class="i-code">0xe867</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe86a" class="width-4-1"><i class="demo-icon desktop">&#xe86a;</i> <span class="i-name">icon-desktop</span><span class="i-code">0xe86a</span></div>
			    <div title="Code: 0xe86b" class="width-4-1"><i class="demo-icon laptop">&#xe86b;</i> <span class="i-name">icon-laptop</span><span class="i-code">0xe86b</span></div>
			    <div title="Code: 0xe86c" class="width-4-1"><i class="demo-icon globe">&#xe86c;</i> <span class="i-name">icon-globe</span><span class="i-code">0xe86c</span></div>
			    <div title="Code: 0xe884" class="width-4-1"><i class="demo-icon off">&#xe884;</i> <span class="i-name">icon-off</span><span class="i-code">0xe884</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe887" class="width-4-1"><i class="demo-icon toggle-on">&#xe887;</i> <span class="i-name">icon-toggle-on</span><span class="i-code">0xe887</span></div>
			    <div title="Code: 0xe888" class="width-4-1"><i class="demo-icon toggle-off">&#xe888;</i> <span class="i-name">icon-toggle-off</span><span class="i-code">0xe888</span></div>
			    <div title="Code: 0xe889" class="width-4-1"><i class="demo-icon tint">&#xe889;</i> <span class="i-name">icon-tint</span><span class="i-code">0xe889</span></div>
			    <div title="Code: 0xe88c" class="width-4-1"><i class="demo-icon chart-pie">&#xe88c;</i> <span class="i-name">icon-chart-pie</span><span class="i-code">0xe88c</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe88d" class="width-4-1"><i class="demo-icon chart-area">&#xe88d;</i> <span class="i-name">icon-chart-area</span><span class="i-code">0xe88d</span></div>
			    <div title="Code: 0xe88e" class="width-4-1"><i class="demo-icon chart-bar">&#xe88e;</i> <span class="i-name">icon-chart-bar</span><span class="i-code">0xe88e</span></div>
			    <div title="Code: 0xe88f" class="width-4-1"><i class="demo-icon chart-line">&#xe88f;</i> <span class="i-name">icon-chart-line</span><span class="i-code">0xe88f</span></div>
			    <div title="Code: 0xe891" class="width-4-1"><i class="demo-icon gauge">&#xe891;</i> <span class="i-name">icon-gauge</span><span class="i-code">0xe891</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe892" class="width-4-1"><i class="demo-icon plug">&#xe892;</i> <span class="i-name">icon-plug</span><span class="i-code">0xe892</span></div>
			    <div title="Code: 0xe893" class="width-4-1"><i class="demo-icon puzzle">&#xe893;</i> <span class="i-name">icon-puzzle</span><span class="i-code">0xe893</span></div>
			    <div title="Code: 0xe894" class="width-4-1"><i class="demo-icon graduation-cap">&#xe894;</i> <span class="i-name">icon-graduation-cap</span><span class="i-code">0xe894</span></div>
			    <div title="Code: 0xe895" class="width-4-1"><i class="demo-icon cube">&#xe895;</i> <span class="i-name">icon-cube</span><span class="i-code">0xe895</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe897" class="width-4-1"><i class="demo-icon shield">&#xe897;</i> <span class="i-name">icon-shield</span><span class="i-code">0xe897</span></div>
			    <div title="Code: 0xe898" class="width-4-1"><i class="demo-icon fire">&#xe898;</i> <span class="i-name">icon-fire</span><span class="i-code">0xe898</span></div>
			    <div title="Code: 0xe899" class="width-4-1"><i class="demo-icon database">&#xe899;</i> <span class="i-name">icon-database</span><span class="i-code">0xe899</span></div>
			    <div title="Code: 0xe89b" class="width-4-1"><i class="demo-icon brush">&#xe89b;</i> <span class="i-name">icon-brush</span><span class="i-code">0xe89b</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe89c" class="width-4-1"><i class="demo-icon diamond">&#xe89c;</i> <span class="i-name">icon-diamond</span><span class="i-code">0xe89c</span></div>
			    <div title="Code: 0xe89d" class="width-4-1"><i class="demo-icon birthday">&#xe89d;</i> <span class="i-name">icon-birthday</span><span class="i-code">0xe89d</span></div>
			    <div title="Code: 0xe89e" class="width-4-1"><i class="demo-icon venus">&#xe89e;</i> <span class="i-name">icon-venus</span><span class="i-code">0xe89e</span></div>
			    <div title="Code: 0xe89f" class="width-4-1"><i class="demo-icon mars">&#xe89f;</i> <span class="i-name">icon-mars</span><span class="i-code">0xe89f</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8a0" class="width-4-1"><i class="demo-icon android">&#xe8a0;</i> <span class="i-name">icon-android</span><span class="i-code">0xe8a0</span></div>
			    <div title="Code: 0xe8a1" class="width-4-1"><i class="demo-icon apple">&#xe8a1;</i> <span class="i-name">icon-apple</span><span class="i-code">0xe8a1</span></div>
			    <div title="Code: 0xe8a2" class="width-4-1"><i class="demo-icon delicious">&#xe8a2;</i> <span class="i-name">icon-delicious</span><span class="i-code">0xe8a2</span></div>
			    <div title="Code: 0xe8a4" class="width-4-1"><i class="demo-icon wechat">&#xe8a4;</i> <span class="i-name">icon-wechat</span><span class="i-code">0xe8a4</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8a5" class="width-4-1"><i class="demo-icon weibo">&#xe8a5;</i> <span class="i-name">icon-weibo</span><span class="i-code">0xe8a5</span></div>
			    <div title="Code: 0xe8a6" class="width-4-1"><i class="demo-icon qq">&#xe8a6;</i> <span class="i-name">icon-qq</span><span class="i-code">0xe8a6</span></div>
			    <div title="Code: 0xe8a7" class="width-4-1"><i class="demo-icon terminal">&#xe8a7;</i> <span class="i-name">icon-terminal</span><span class="i-code">0xe8a7</span></div>
			    <div title="Code: 0xe8a8" class="width-4-1"><i class="demo-icon ellipsis-vert">&#xe8a8;</i> <span class="i-name">icon-ellipsis-vert</span><span class="i-code">0xe8a8</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8a9" class="width-4-1"><i class="demo-icon cw">&#xe8a9;</i> <span class="i-name">icon-cw</span><span class="i-code">0xe8a9</span></div>
			    <div title="Code: 0xe8aa" class="width-4-1"><i class="demo-icon exchange">&#xe8aa;</i> <span class="i-name">icon-exchange</span><span class="i-code">0xe8aa</span></div>
			    <div title="Code: 0xe8ab" class="width-4-1"><i class="demo-icon sliders">&#xe8ab;</i> <span class="i-name">icon-sliders</span><span class="i-code">0xe8ab</span></div>
			    <div title="Code: 0xe8ae" class="width-4-1"><i class="demo-icon sort">&#xe8ae;</i> <span class="i-name">icon-sort</span><span class="i-code">0xe8ae</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8af" class="width-4-1"><i class="demo-icon sort-down">&#xe8af;</i> <span class="i-name">icon-sort-down</span><span class="i-code">0xe8af</span></div>
			    <div title="Code: 0xe8b0" class="width-4-1"><i class="demo-icon sort-up">&#xe8b0;</i> <span class="i-name">icon-sort-up</span><span class="i-code">0xe8b0</span></div>
			    <div title="Code: 0xe8b1" class="width-4-1"><i class="demo-icon sort-alt-up">&#xe8b1;</i> <span class="i-name">icon-sort-alt-up</span><span class="i-code">0xe8b1</span></div>
			    <div title="Code: 0xe8b2" class="width-4-1"><i class="demo-icon sort-alt-down">&#xe8b2;</i> <span class="i-name">icon-sort-alt-down</span><span class="i-code">0xe8b2</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8b3" class="width-4-1"><i class="demo-icon play-1">&#xe8b3;</i> <span class="i-name">icon-play-1</span><span class="i-code">0xe8b3</span></div>
			    <div title="Code: 0xe8b4" class="width-4-1"><i class="demo-icon pause-1">&#xe8b4;</i> <span class="i-name">icon-pause-1</span><span class="i-code">0xe8b4</span></div>
			    <div title="Code: 0xe8b5" class="width-4-1"><i class="demo-icon stop-1">&#xe8b5;</i> <span class="i-name">icon-stop-1</span><span class="i-code">0xe8b5</span></div>
			    <div title="Code: 0xe8b6" class="width-4-1"><i class="demo-icon to-end-1">&#xe8b6;</i> <span class="i-name">icon-to-end-1</span><span class="i-code">0xe8b6</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8b7" class="width-4-1"><i class="demo-icon to-start-1">&#xe8b7;</i> <span class="i-name">icon-to-start-1</span><span class="i-code">0xe8b7</span></div>
			    <div title="Code: 0xe8b8" class="width-4-1"><i class="demo-icon fast-forward">&#xe8b8;</i> <span class="i-name">icon-fast-forward</span><span class="i-code">0xe8b8</span></div>
			    <div title="Code: 0xe8b9" class="width-4-1"><i class="demo-icon fast-backward">&#xe8b9;</i> <span class="i-name">icon-fast-backward</span><span class="i-code">0xe8b9</span></div>
			    <div title="Code: 0xe8ba" class="width-4-1"><i class="demo-icon down-1">&#xe8ba;</i> <span class="i-name">icon-down-1</span><span class="i-code">0xe8ba</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8bb" class="width-4-1"><i class="demo-icon left-1">&#xe8bb;</i> <span class="i-name">icon-left-1</span><span class="i-code">0xe8bb</span></div>
			    <div title="Code: 0xe8bc" class="width-4-1"><i class="demo-icon right-1">&#xe8bc;</i> <span class="i-name">icon-right-1</span><span class="i-code">0xe8bc</span></div>
			    <div title="Code: 0xe8bd" class="width-4-1"><i class="demo-icon up-1">&#xe8bd;</i> <span class="i-name">icon-up-1</span><span class="i-code">0xe8bd</span></div>
			    <div title="Code: 0xe8be" class="width-4-1"><i class="demo-icon download">&#xe8be;</i> <span class="i-name">icon-download</span><span class="i-code">0xe8be</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8bf" class="width-4-1"><i class="demo-icon upload">&#xe8bf;</i> <span class="i-name">icon-upload</span><span class="i-code">0xe8bf</span></div>
			    <div title="Code: 0xe8c0" class="width-4-1"><i class="demo-icon lock-1">&#xe8c0;</i> <span class="i-name">icon-lock-1</span><span class="i-code">0xe8c0</span></div>
			    <div title="Code: 0xe8c1" class="width-4-1"><i class="demo-icon lock-alt">&#xe8c1;</i> <span class="i-name">icon-lock-alt</span><span class="i-code">0xe8c1</span></div>
			    <div title="Code: 0xe8c2" class="width-4-1"><i class="demo-icon lock-open-1">&#xe8c2;</i> <span class="i-name">icon-lock-open-1</span><span class="i-code">0xe8c2</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8c3" class="width-4-1"><i class="demo-icon lock-open-alt-1">&#xe8c3;</i> <span class="i-name">icon-lock-open-alt-1</span><span class="i-code">0xe8c3</span></div>
			    <div title="Code: 0xe8c4" class="width-4-1"><i class="demo-icon folder-1">&#xe8c4;</i> <span class="i-name">icon-folder-1</span><span class="i-code">0xe8c4</span></div>
			    <div title="Code: 0xe8c5" class="width-4-1"><i class="demo-icon folder-open-1">&#xe8c5;</i> <span class="i-name">icon-folder-open-1</span><span class="i-code">0xe8c5</span></div>
			    <div title="Code: 0xe8c6" class="width-4-1"><i class="demo-icon cog-1">&#xe8c6;</i> <span class="i-name">icon-cog-1</span><span class="i-code">0xe8c6</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8c7" class="width-4-1"><i class="demo-icon chart-bar-1">&#xe8c7;</i> <span class="i-name">icon-chart-bar-1</span><span class="i-code">0xe8c7</span></div>
			    <div title="Code: 0xe8c8" class="width-4-1"><i class="demo-icon note-beamed">&#xe8c8;</i> <span class="i-name">icon-note-beamed</span><span class="i-code">0xe8c8</span></div>
			    <div title="Code: 0xe8ca" class="width-4-1"><i class="demo-icon spin6 animate-spin">&#xe8ca;</i> <span class="i-name">icon-spin6</span><span class="i-code">0xe8ca</span></div>
			    <div title="Code: 0xe8cb" class="width-4-1"><i class="demo-icon spin3 animate-spin">&#xe8cb;</i> <span class="i-name">icon-spin3</span><span class="i-code">0xe8cb</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8ce" class="width-4-1"><i class="demo-icon upload-cloud">&#xe8ce;</i> <span class="i-name">icon-upload-cloud</span><span class="i-code">0xe8ce</span></div>
			    <div title="Code: 0xe8d0" class="width-4-1"><i class="demo-icon magnet">&#xe8d0;</i> <span class="i-name">icon-magnet</span><span class="i-code">0xe8d0</span></div>
			    <div title="Code: 0xe8d1" class="width-4-1"><i class="demo-icon beaker">&#xe8d1;</i> <span class="i-name">icon-beaker</span><span class="i-code">0xe8d1</span></div>
			    <div title="Code: 0xe8d2" class="width-4-1"><i class="demo-icon truck">&#xe8d2;</i> <span class="i-name">icon-truck</span><span class="i-code">0xe8d2</span></div>
			  </div>
			  <div class="row">
			    <div title="Code: 0xe8d3" class="width-4-1"><i class="demo-icon building">&#xe8d3;</i> <span class="i-name">icon-building</span><span class="i-code">0xe8d3</span></div>
			    <div title="Code: 0xe8d4" class="width-4-1"><i class="demo-icon food">&#xe8d4;</i> <span class="i-name">icon-food</span><span class="i-code">0xe8d4</span></div>
			    <div title="Code: 0xe8d5" class="width-4-1"><i class="demo-icon coffee">&#xe8d5;</i> <span class="i-name">icon-coffee</span><span class="i-code">0xe8d5</span></div>
			    <div title="Code: 0xe8d6" class="width-4-1"><i class="demo-icon sitemap">&#xe8d6;</i> <span class="i-name">icon-sitemap</span><span class="i-code">0xe8d6</span></div>
			  </div>
			</div>
		</div>
	',
	'scripts' => '

	'
);

return $options;