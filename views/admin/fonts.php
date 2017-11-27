<?php echo $this->tpl('start');?>

<style>
.font-icon{
	color: #555;
	padding:3px 5px;
	border-radius: 3px;
	font-size: 14px;
}
.font-icon:hover{
	color: #333;
	background: #fafafa;
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

<?php echo $this->tpl('header');?>

<section class="page-main" role="main">
	<div class="container" id="icons">
		<div class="main-panel">
			<div class="fr">
				<input type="text" class="form-control" placeholder="搜索图标..." data-bind="value:search,valueUpdate:'keyup'" />
			</div>
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

<?php echo $this->tpl('scripts');?>

<script type="text/javascript">
	var a = new Ajax, path = _CONFIG_.path;
	var Model = function(){
		this.iconPrefix = '';
		this.data = ko.observableArray();
		this.search = ko.observable('');
		this.icons = ko.computed(function() {
			var searchText = this.search().trim();
			return this.data().filter(function(row){
				if(searchText === ''){
					return row;
				}else{
					if(row.css.indexOf(searchText) >= 0){
						return row;
					}
				}
			});
		}, this);
	}
	var viewModel = new Model;
	ko.applyBindings(viewModel,document.getElementById('icons'));

	a.post(path+'assets/fonts/config.json',null,function (res) {
		viewModel.iconPrefix = res.css_prefix_text;
		viewModel.data(res.glyphs);
	})
</script>

<?php echo $this->tpl('end');?>