var appMenu = Vue.extend({
	props:['path'],
	data : function(){
		return {
			active : -1,
			data : []
		}
	},
	template:'<li v-for="row in data" v-bind:class="{active:active==$index}">'+
				'<a href="javascript:void(0);" @click="showMenu($index)">'+
					'<i :class="row.icon"></i>&nbsp;'+
					'<span v-text="row.name"></span>'+
					'<span class="arrow"><i v-bind:class="[active==$index ? \'icon-up-dir\' :\'icon-down-dir\']"></i></span>'+
				'</a>'+
				'<ul class="child" v-if="active==$index" class="animate" transition="fade">'+
					'<li v-for="row in row.menu">'+
						'<a v-text="row.title" href="{{path}}{{row.url}}" title="{{row.title}}"></a>'+
					'</li>'+
				'</ul>'+
			'</li>',
	methods:{
		showMenu:function(index){
			this.active = (this.active==index) ? -1 : index;
		}
	},
	ready:function(){
		var self = this;
		this.$http.get(this.path+'admin/get_app_menu').then(function(response){
			self.data = response.data;
		});
	}
});

var app = new Vue({
	el : '#app',
	data : data,
	components:{
		'app-menu':appMenu
	},
	methods:{
		changeTheme:function(theme){
			this.themeCurrent = theme;
		}
	}
});