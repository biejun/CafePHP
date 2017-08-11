<?php $this->show('start');?>

<style>
.font-icon{
	color: #555;
	padding:3px 5px;
	border-radius: 3px;
	font-size: 14px;
}
.font-icon:hover{
	color: #333;
	background: #fff;
	padding-left: 10px;
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
			<div class="right-ribbons">
				<input type="text" class="search-input" placeholder="搜索图标..." data-bind="value:search,valueUpdate:'keyup'" />
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

<?php $this->show('scripts');?>

<script type="text/javascript">
	(function(a,c){
		var viewModel = function(){
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
		var vm = new viewModel();
		ko.applyBindings(vm,document.getElementById('icons'));
		setTimeout(function(){
			a.get(c.path + 'assets/fonts/config.json'
				,{}
				,function(res){
					if(!res) return false;
					var data = a.jsonParse(res);
					vm.iconPrefix = data.css_prefix_text;
					vm.data(data.glyphs);
				}
			);
		},1000);

	})(ajax,_CONFIG_);
</script>

<?php $this->show('end');?>