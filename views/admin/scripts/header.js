(function(c,req){
	var Nav = function(title,url,child){
		this.title = title;
		this.url = url;
		this.child = child || false;
	}
	var Header = function(){
		this.currentPath = new req().path;
		this.nav = ko.observableArray([
				new Nav('控制台','javascript:;',[
					new Nav('网站概要',c.path+'admin/console')
					,new Nav('缓存文件',c.path+'admin/console/cache')
					,new Nav('临时文件',c.path+'admin/console/temp')
					,new Nav('数据备份',c.path+'admin/console/backup')
				])
				,new Nav('设置',c.path+'admin/settings')
				,new Nav('图标',c.path+'admin/fonts')
		]);

		this.user = ko.observableArray([
				new Nav(c.loginUser,'javascript:;',[
					new Nav('个人资料',c.path+'admin/account/profile?do=edit')
					,new Nav('网站前台',c.path)
					,new Nav('用户管理',c.path+'admin/account/operation')
					,new Nav('添加用户',c.path+'admin/account/add')
				])
				,new Nav('退出登录',c.path+'admin/logout')
		]);
	}

	ko.applyBindings(new Header(),document.getElementById('header'));
})(_CONFIG_,Request);