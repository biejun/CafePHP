<header id="header" class="header" role="header">
	<ul data-bind="foreach:user" class="nav user pd cf fr">
		<li data-bind="css:{'hasChild':child}">
			<a data-bind="attr:{'href':url,'title':title}">
				<span data-bind="text:title"></span>
				<!-- ko if:child -->
				<i class="icon icon-angle-down"></i>
				<!-- /ko -->
			</a>
			<!-- ko if:child -->
			<ul class="pd" data-bind="foreach:child">
				<li><a data-bind="text:title,attr:{'href':url}"></a></li>
			</ul>
			<!-- /ko -->
		</li>
	</ul>
	<ul class="nav pd cf" data-bind="foreach:nav">
		<li data-bind="css:{'active': url === $parent.currentPath,'hasChild':child}">
			<a data-bind="attr:{'href':url,'title':title}">
				<span data-bind="text:title">控制台</span>
				<!-- ko if:child -->
				<i class="icon icon-angle-down"></i>
				<!-- /ko -->
			</a>
			<!-- ko if:child -->
			<ul class="pd" data-bind="foreach:child">
				<li><a data-bind="text:title,attr:{'href':url}"></a></li>
			</ul>
			<!-- /ko -->
		</li>
	</ul>
</header>

<div id="notify" class="notify" data-bind="css:{'show':notifyToShow}">
	<div data-bind="attr:{'class':'notify-box ' + type()}">
		<div data-bind="text:msg"></div>
		<button type="button" class="close" data-bind="text:'&times;',click:function(){notifyToShow(false)}"></button>
	</div>
</div>