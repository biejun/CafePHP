<?php $this->show('start');?>

<style>
.font-icon{
	color: #555;
	padding:3px 5px;
	border-radius: 5px;
	font-size: 14px;
}
.font-icon:hover{
	color: #333;
	background: #fff;
	padding-left: 10px;
	cursor: pointer;
}
.the-icon {
	font-family: "fontello";
	font-style: normal;
	font-weight: normal;
	speak: none;
	display: inline-block;
	text-decoration: inherit;
	width: 1em;
	margin-right: .5em;
	text-align: center;
	font-variant: normal;
	text-transform: none;
	line-height: 1em;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	font-size: 14px;
	line-height: 24px;
}
</style>
<?php $this->show('header');?>

<section class="page-main" role="main">
	<div class="container" id="icons">
		<div class="main-panel">
			<h2>字体图标</h2>
		</div>
		<div class="row" data-bind="foreach:icons">
			<div class="width-4-1 font-icon">
				<i data-bind="attr:{'class':'the-icon '+$parent.iconPrefix+css}"></i>
				<span data-bind="text:$parent.iconPrefix+css"></span>
			</div>
		</div>
	</div>
</section>

<?php $this->show('scripts');?>

<script type="text/javascript">
	(function(a,c){
		var viewModel = function(){
			this.iconPrefix = '';
			this.icons = ko.observableArray();
		}
		var vm = new viewModel();
		ko.applyBindings(vm,document.getElementById('icons'));
		setTimeout(function(){
			a.get(c.path + 'assets/fonts/config.json'
				,{}
				,function(res){
					if(!res) return false;
					var data = a.jsonParse(res);
					vm.iconPrefix = data.css_prefix_text;
					vm.icons(data.glyphs);
				}
				,function failed(){

				}
			);
		},1000);
	})(ajax,_CONFIG_);
</script>

<?php $this->show('end');?>