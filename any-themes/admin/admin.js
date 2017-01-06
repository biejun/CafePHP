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
		if(this.parentNode.classList.contains('menu-active')){
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

if(bindMenu!=='') currentMenu.classList.add('menu-active');

if(bindMenu.indexOf('child')>0){
	var parentNode = bindMenu.substring(0,bindMenu.indexOf('child')-1) ,
		parentMenu = document.getElementById(parentNode) ,
		eve = document.createEvent('Event');
	eve.initEvent('click', true, true);
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