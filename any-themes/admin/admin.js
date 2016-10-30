// var userDropdown = Vue.extend({
// 	props:['path','show'],
// 	template:'#userDropdown',
// 	data:function(){
// 		return {
// 			userSetView : '1'
// 		}
// 	}
// });
// var appMenu = Vue.extend({
// 	props:['path'],
// 	data : function(){
// 		return {
// 			active : -1,
// 			data : []
// 		}
// 	},
// 	template:'<li v-for="row in data" v-bind:class="{active:active==$index}">'+
// 				'<a href="javascript:void(0);" @click.stop.prevent="showMenu($index)">'+
// 					'<i :class="row.icon"></i>&nbsp;'+
// 					'<span v-text="row.name"></span>'+
// 					'<span class="arrow"><i v-bind:class="[active==$index ? \'icon-up-dir\' :\'icon-down-dir\']"></i></span>'+
// 				'</a>'+
// 				'<ul class="child" v-if="active==$index" class="animate" transition="fade">'+
// 					'<li v-for="row in row.menu">'+
// 						'<a v-text="row.title" href="{{row.url}}" title="{{row.title}}"></a>'+
// 					'</li>'+
// 				'</ul>'+
// 			'</li>',
// 	events:{
// 		offClick:function(){
// 			if(this.active!=-1)
// 				this.active = -1;
// 		}
// 	},
// 	methods:{
// 		showMenu:function(index){
// 			this.active = (this.active==index) ? -1 : index;
// 		}
// 	},
// 	ready:function(){
// 		var self = this;
// 		this.$http.get(this.path+'admin/get_app_menu').then(function(response){
// 			self.data = response.data;
// 		});
// 	}
// });

// var app = new Vue({
// 	el : '#app',
// 	data : data,
// 	components:{
// 		'app-menu':appMenu,
// 		'user-dropdown':userDropdown
// 	},
// 	events:{
// 		offClick:function(){
// 			if(this.userDropDown) this.userDropDown = false;
// 		}
// 	},
// 	methods:{
// 		changeTheme:function(theme){
// 			this.themeCurrent = theme;
// 		}
// 	},
// 	ready:function(){
// 		document.addEventListener('click',function(){
// 			this.$emit('offClick');
// 			this.$broadcast('offClick');
// 		}.bind(this))
// 	}
// });

var menuItem = document.querySelectorAll('.menu-item a');

for(var i =0;i < menuItem.length;i++){
	menuItem[i].addEventListener('click',function(e){
		var classList = this.parentNode.classList;
		var angleIcon = this.querySelectorAll('.fr')[0];
		var childNode = this.nextElementSibling;
		var siblings = this.parentNode.parentNode.children;
		if(siblings && siblings.length>1 && childNode){
			for(var s = 0; s < siblings.length;s++){
				if(siblings[s]!=this.parentNode){
					siblings[s].classList.remove('menu-active');
					var siblingsIcon = siblings[s].querySelectorAll('.fr')[0];
					siblingsIcon.className = siblingsIcon.className.replace('down','right')
				}
			}
		}
		if(this.parentNode.className.indexOf('menu-active')>0){
			classList.remove('menu-active');
			if(childNode && angleIcon){
				angleIcon.className = angleIcon.className.replace('down','right')
			}
		}else{
			classList.add('menu-active');
			if(childNode && angleIcon){
				angleIcon.className = angleIcon.className.replace('right','down')
			}
		}
	});
}

var bindMenu = document.body.getAttribute('data-bind');
var currentMenu = document.getElementById(bindMenu);

currentMenu.classList.add('menu-active');

if(bindMenu.indexOf('child')>0){
	var parentNode = bindMenu.substring(0,bindMenu.indexOf('child')-1);
	var parentMenu = document.getElementById(parentNode);

	if (window.CustomEvent) {
		var eve = new CustomEvent('click');
	}else{
		var eve = document.createEvent('click');
		eve.initCustomEvent('click', true, true);
	}
	parentMenu.querySelectorAll('a')[0].dispatchEvent(eve);
}

function toggleMenu(){
	var body = document.body;
	if(body.className.indexOf('hideNav')!=-1){
		body.classList.remove('hideNav');
	}else{
		body.classList.add('hideNav');
	}
}