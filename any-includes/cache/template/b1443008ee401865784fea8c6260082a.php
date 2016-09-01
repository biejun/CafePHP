<header class="navbar">
	
</header>
<nav class="nav">
	<div class="menu">
		<ul>
			<li>
				<a class="menu-item" href="<?php echo $path ;?>" title="返回网站首页">
					<i class="icon-desktop"></i>
					<div class="menu-name">网站首页</div>
				</a>
			</li>
			<li v-bind:class="[active==1 ? 'active' :'']">
				<a class="menu-item" href="<?php echo $path ;?>admin/index.html" title="仪表盘">
					<i class="icon-gauge"></i>
					<div class="menu-name">仪表盘</div>
				</a>
			</li>
			<li v-bind:class="[active==2 ? 'active' :'']">
				<a class="menu-item" href="<?php echo $path ;?>admin/setting.html">
					<i class="icon-cog"></i>
					<div class="menu-name">设置</div>
				</a>
			</li>
			<li v-bind:class="[active==3 ? 'active' :'']">
				<a class="menu-item" href="<?php echo $path ;?>admin/application.html">
					<i class="icon-plug"></i>
					<div class="menu-name">应用</div>
				</a>
			</li>
			<li v-bind:class="[active==4 ? 'active' :'']">
				<a class="menu-item" href="<?php echo $path ;?>admin/theme.html">
					<i class="icon-brush"></i>
					<div class="menu-name">主题</div>
				</a>
			</li>
			<app-menu :path="path"></app-menu>
			<li v-bind:class="[active==5 ? 'active' :'']">
				<a class="menu-item" href="<?php echo $path ;?>admin/fontello.html">
					<i class="icon-flag"></i>
					<div class="menu-name">字体图标</div>
				</a>
			</li>
		</ul>
	</div>
</nav>
<script type="text/javascript">
Vue.transition('fade', {
	enterClass: 'fadeInLeft',
	leaveClass: 'fadeOut'
});
Vue.component('app-menu',{
	props:['path'],
	data : function(){
		return {
			active : -1,
			data : <?php echo $menu ;?>
		}
	},
	template:'<li v-for="row in data" v-bind:class="[active==$index ? \'active\' :\'\']">'+
				'<a class="menu-item" href="javascript:void(0);" @click="showMenu($index)">'+
					'<i :class="row.icon"></i>&nbsp;'+
					'<div class="menu-name" v-text="row.name"></div>'+
				'</a>'+
				'<ul v-if="active==$index" class="animate" transition="fade">'+
					'<li v-for="row in row.menu">'+
						'<a v-text="row.title" href="{{path}}{{row.url}}" title="{{row.title}}"></a>'+
					'</li>'+
				'</ul>'+
			'</li>',
	methods:{
		showMenu:function(index){
			if(this.active==index){
				this.active = -1;
			}else{
				this.$parent.active = -1;
				this.active = index;
			}
		}
	}
});
</script>