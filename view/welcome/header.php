<aside id="ko-nav" class="page-header">
  <div class="page-header__inner">
	  <div class="page-header__left">
	  	  管理后台
	  </div>
	  <nav class="page-header__center page-nav" data-bind="foreach:nav">
	  	<div class="page-nav__item" 
		 data-bind="css:{'active': url === $parent.currentPath}">
	  		<!-- ko ifnot:hasChild -->
	  		<a data-bind="attr:{'href':url,'title':title}, text:title"></a>
	  		<!-- /ko -->
	  		<!-- ko if:hasChild -->
	  		<div class="ui dropdown" data-bind="dropdown: true">
	  		  <div class="text" data-bind="if: url==='none'">
	  			  <span data-bind="text:title"></span>
	  		  </div>
	  		  <i class="iconfont icon-unfold"></i>
	  			<div class="menu" data-bind="foreach: child">
	  			  <a class="item" 
	  			    data-bind="attr:{'href':url,'title':title}, text:title">
	  			  </a>
	  			</div>
	  		</div>
	  		<!-- /ko -->
	  	</div>
	  </nav>
	  <div class="page-header__right page-nav">
		<div class="page-nav__item">
			<div class="ui dropdown">
			  <div class="text" data-bind="text: userName"></div>
			  <i class="iconfont icon-unfold"></i>
			  <div class="menu" data-bind="foreach:userNav">
			    <a class="item" data-bind="attr:{'href':url,'title':title}, text:title"></a>
			  </div>
			</div>
		</div>
	  </div>
  </div>
</aside>