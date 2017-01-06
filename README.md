# ANYPHP V 2.0
> 对未完成的V1.0版进行了90%的代码重写，使敏捷开发变得更加规范和优雅，这才是真正的ANYPHP所应该有的特性。

目前还在开发完善阶段，您可以先下载下来玩玩或和我一起利用闲暇时间来完善它，交流与反馈可在新浪微博（@别小俊）上与我联系。

## 环境配置与安装    

服务器环境支持Apache/Nginx/PHP 5.4+/MySql 5.5+，如果您的服务器配置低于这个要求，建议您先升级。安装程序请访问：/install.php，并进行一些必要的配置。

## 介绍    

对于前端开发人员来说，懂一门后端开发语言是很有必要的，而在Python、PHP、Node、Ruby、GO语言流行的今天，有太多太多种后端语言供我们选择和学习了，我们在扩展视野的同时，也不能过于盲目的选择和去学习任何一种语言或框架，那样只会浪费更多的时间和精力。对于前端开发人员来说，选一门入门容易的后端语言，只需略懂就能快速开发做项目是最好的选择，ANYPHP因此而生。   

ANYPHP并不是一套框架，而是一套快速构建项目的解决方案。利用php语言灵活自由的特性，在做数据请求和处理的过程中，发挥超酷的作用。当然这套方案也不仅仅局限于php中，将来还有可能会在NodeJS、GOlang中发挥作用，创造出如ANYNODE、ANYGO等等一系列程序，为前端开发人员提供快速学习，敏捷开发的解决方案。

在这里，我们推荐您使用VueJS来构建前端架构，因为它是一个进步的js框架，吸收了angularJS和ReactJs优秀的特性，当然代码同样也非常优雅，在做数据视图绑定上，性能要比PHP快一倍，这也是为什么ANYPHP没有加入页面模板标签功能的原因，通过学习时间上的对比，学习vue要比学习react更快，更容易上手。ANYPHP中默认集合了1.0版本的VueJS和Webpack的配置文件，您只需在当前程序目录下（Win: 按住Shift+鼠标右键打开命令行工具,MacOS: 打开终端，将anyphp程序文件夹拖拽到dock终端图标上）输入命令 npm install即可完成的依赖安装（需要预先安装NodeJs才能执行npm命令），如果不懂如何构建，您可以上vuejs.org查看官方的教程或通过下面的教程来学习如何构建一个Vue的项目。

## 教程

#### 创建应用

首先，我们在any-apps文件夹中，新建一个文件夹，命名为"demo"。然后在"demo"文件夹中添加一个应用描述文件，保存文件名为"description.php"，写入代码：   

````php
<?php
return array(
	'app'=>'demo',	// 应用名，必须与文件夹命名一致
	'name'=>'演示',	//	应用中文名
	'description'=>'这个应用主要为了展示Hello World',	// 应用秒速
	'version'=>'1.0.0',	// 应用版本
	'author'=>'作者名',	// 应用作者
	'date'=>'2016.06.23',	// 应用创建时间
	'icon'=>'icon-user',	// 应用图标，可在后台查看所有图标
	'special'=>false,
	'options'=>array(	// 应用安装后添加到管理后台的菜单
		array('全部用户','admin.php')	// 第一个参数为菜单名，第二个参数为菜单所绑定的页面
	)
);
````

#### 创建路由

上一步我们创建了一个名为"demo"的应用，下面，我们继续来完善这个应用，给它添加路由，让它支持浏览器访问。    

首先，我们新建一个名为"route.php"的文件，然后在这个文件中添加多种路由规则。    

基本 GET 路由：   

````php
Route::get('/',function($ui){
	echo 'hello World!';
});
````   

其他基础路由：

````php
Route::post('/foo/bar',function($ui){
	echo 'hello World!';
});

Route::put('/foo/bar',function($ui){

});

Route::delete('/foo/bar',function($ui){

});
````

带参数的路由：
````php
// http://localhost/page/2
Route::get('/page/:id',function($ui,$id){
	echo 'hello World!';
	echo $id; // 2
});
````  
多参数的路由：
````php
// http://localhost/page/2/desc
Route::get('/page/:id/:sort',function($ui,$id,$sort){
	echo 'hello World!';
	echo $id; // 2
	echo $sort; // desc
});
````  
?懒惰匹配：
````php
// http://localhost/page/2
Route::get('/page/:id/:sort?',function($ui,$id,$sort){
	echo 'hello World!';
	echo $id; // 2
	echo $sort; // null
});
````  
