(function(req, $http){
  var basePath = document.getElementsByTagName('meta')['path'].content;
  var navPath = basePath + 'admin';
  var Nav = function(title, url, child){
	if(url instanceof Array 
	  && typeof child === 'undefined') {
		child = url;
		url = 'none';
	}
    this.title = title;
    this.url = url;
    this.child = child || [];
	this.hasChild = !!(child && child.length > 0);
  }
  var Header = function(isAdmin, level){
    this.currentPath = req.path;
	this.level = ko.observable(0);
    this.nav = ko.observableArray([
        new Nav('首页',navPath+'/index')
    ]);
	this.userNav = ko.observableArray([
		new Nav('个人资料',navPath+'/profile?do=edit')
		,new Nav('网站前台',basePath)
		,new Nav('退出登录',basePath+'logout')
	]);
    this.nav.push(new Nav('控制台', [
        new Nav('用户管理',navPath+'/console/users'),
        new Nav('系统设置',navPath+'/console/config'),
        new Nav('数据备份',navPath+'/console/backup'),
        new Nav('系统日志',navPath+'/console/log'),
    ]));
  }
  
  var header = new Header();
  var $nav = $('#ko-nav');
  ko.applyBindings(header, $nav.get(0));
  
  $nav.find('.ui.dropdown').dropdown({
  	on: 'click',
  	action: 'nothing'
  });

})(new UrlRequest, new Ajax);