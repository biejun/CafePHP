<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/layout.css')
    ->css('/admin/css/index.css', '1.0.12');
?>

<style>
	.meta-form{
		width: 350px!important;
	}
	.meta-form .extra-table{
		max-height: 280px;
		overflow: auto;
	}
</style>

<?php $this->stop() ?>



<section class="page-container">

  <?php $this->insert('header')?>
  
  <main class="page-main" id="app">
	  <div class="main-header-panel">
		<div class="header-panel__left">
			<h2>系统设置</h2>
		</div>
	  	<div class="header-panel__right">
			<button class="ui green small button" data-bind="click:addItem">
			  新建配置
			</button>
			<button type="submit" class="ui primary small button" data-bind="click:submit, disable: newItems().length>0">保存配置</button>
	  	</div>
	  </div>
	  <div class="main-content-panel">
		<div data-bind="if:newItems().length>0">
			<table class="ui celled small table layout-table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>
							<button type="button" class="icon-btn add" data-bind="click:$root.removeItem.bind($root,$data)">
								<i class="iconfont icon-minus-circle-fill"></i>
							</button>
						</th>
						<th><span class="red-text">*</span>配置项名称</th>
						<th><span class="red-text">*</span>配置项英文</th>
						<th>配置项类型</th>
						<th width="300" colspan="2">配置项校验规则</th>
						<th>配置项默认值</th>
						<th>必填项</th>
					</tr>
				</thead>
				<tbody data-bind="foreach:newItems">
					<tr>
						<td>
							<button type="button" 
							  class="icon-btn remove" 
							  data-bind="click:$root.removeItem.bind($root,$data)">
								<i class="iconfont icon-plus-circle-fill"></i>
							</button>
						</td>
						<td>
							<div class="ui mini form">
								<input type="text" data-bind="value:alias"/>
							</div>
						</td>
						<td>
							<div class="ui mini form">
								<input type="text" data-bind="value:name"/>
							</div>
						</td>
						<td>
							<div class="ui mini form">
								<select data-bind="options:optionTypes,
												value:type,
												optionsText:'text',
												optionsValue:'value',
												selectDropdown: true">
								</select>
							</div>
						</td>
						<td width="300" colspan="2" class="child-table">
							<table class="ui small table" cellpadding="0" cellspacing="0" >
								<!-- ko if:type() != 'textarea'-->
								<thead>
									<tr>
										<th style="width:50%">数据类型</th>
										<th style="width:50%">附加选项</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="width:50%" class="ui mini form">
											<select data-bind="options:optionRules().options,
																optionsText:'text',
																value:datatype,
																disable:type() === 'switch',
																optionsValue:'value',
																selectDropdown: true"></select>
										</td>
										<td style="width:50%">
											<a href="javascript:;" class="relative" data-bind="popup: true" data-position="top center">
												<span>配置项描述</span>
												<div class="ui left transition popup meta-form">
													<div class="ui mini form">
														<div class="field">
															<label>用于描述此配置项</label>
															<input type="text" 
															  placeholder="请输入描述" 
															  data-bind="value: description">
														</div>
													</div>
												</div>
											</a>
											<!--ko if:optionRules().extra-->
												<span class="green-text">|</span>
												<a href="javascript:;" 
												   class="relative" 
												   data-bind="popup: true" 
												   data-position="top center">
													<span>添加选择项</span>
													<div class="ui left transition popup meta-form">
														<div class="extra-table">
															<table class="ui small very basic celled table">
																<thead>
																<tr>
																	<th>
																		<button type="button" class="icon-btn add" 
																		  data-bind="click: addExtra">
																			<i class="iconfont icon-plus-circle-fill"></i>
																		</button>
																	</th>
																	<th>选项名</th>
																	<th>选项值</th>
																</tr>
																</thead>
																<tbody data-bind="foreach:optionRules().extra">
																<tr>
																	<td>
																		<button type="button" class="icon-btn remove"
																		  data-bind="click: $parent.removeExtra.bind($parent, $data)">
																			<i class="iconfont icon-minus-circle-fill"></i>
																		</button>
																	</td>
																	<td data-label="选项名">
																	 <div class="ui mini input">
																		 <input type="text" data-bind="value: key">
																	 </div>
																	</td>
																	<td data-label="选项值">
																	  <div class="ui mini input">
																		  <input type="text" data-bind="value: value">
																	  </div>
																	</td>
																</tr>
																</tbody>
															</table>
														</div>
													</div>
												</a>
											<!-- /ko -->

										</td>
									</tr>
								</tbody>
								<!-- /ko -->
								<!-- ko if:type() === 'textarea'-->
								<thead>
									<tr data-bind="foreach:optionRules().options">
										<th style="width:33.333%" data-bind="text:text"></th>
									</tr>
								</thead>
								<tbody>
									<tr data-bind="foreach:optionRules().options">
										<td style="width:33.333%">
											<!-- ko if:value!=''-->
											<div class="ui mini form">
												<input type="number" data-bind="value:value"/>
											</div>
											<!-- /ko -->
											<!-- ko if:value===''-->
											<a href="javascript:;" class="relative" data-bind="popup: true">
												<span>配置项描述</span>
												<div class="ui left transition popup meta-form">
													<div class="ui mini form">
														<div class="field">
															<label>用于描述此配置项</label>
															<input type="text" placeholder="请输入描述" data-bind="value: $parent.description">
														</div>
													</div>
												</div>
											</a>
											<!-- /ko -->
										</td>
									</tr>
								</tbody>
								<!-- /ko -->
							</table>							
						</td>
						<td>
							<div class="ui mini form">
								<input type="text" data-bind="value:value"/>
							</div>
						</td>
						<td>
							<div class="ui checkbox">
								<input type="checkbox" data-bind="attr:{'id':'isRequire-'+id},checked:required" class="checkbox"/>
								<label data-bind="attr:{'for':'isRequire-'+id}">&nbsp;</label>	
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div>
				<div class="fr">
					<button type="submit" class="ui basic small button" data-bind="click:removeItems">取消</button>
					<button type="submit" class="ui primary small button" data-bind="click:addOptions">添加</button>
				</div>
			</div>
		</div>
		<div data-bind="if:newItems().length===0">
			<table class="ui very basic table layout-table" cellpadding="0" cellspacing="0" >
				<thead>
					<tr>
						<th>配置项</th>
						<th>配置值</th>
						<th>配置描述</th>
					</tr>
				</thead>
				<tbody data-bind="foreach:data">
					<tr>
						<td width="250" align="right">
							<div><strong data-bind="text:alias"></strong></div>
							<div><span class="gray-text" data-bind="text:name"></span></div>
						</td>
						<td>
							<div data-bind="visible:$parent.data" class="ui form" style="display:none;">
								<!-- ko if:type == 'input' -->
									<!-- ko if:datatype != 'checkbox' || datatype != 'radio' -->
									<input data-bind="value:value,
													attr:{type:datatype},
													css:{ error: value.hasError }">
									<!-- /ko -->
									<!-- ko if:datatype == 'checkbox' || datatype == 'radio' -->
									<div class="ui checkbox">
									  <input data-bind="value:value,attr:{type:datatype,id:name}">
									  <label data-bind="attr:{for:name}"></label>
									</div>
									<!-- /ko -->
								<!-- /ko -->
								<!-- ko if:type == 'textarea' -->
									<textarea data-bind="value:value,attr:pattern,css:{ error: value.hasError }" class="form-control"></textarea>
								<!-- /ko -->
								<!-- ko if:type == 'switch' -->
									<input class="switch" hidden type="checkbox" data-bind="checked:value,attr:{'id':name},value:value" />
									<label data-bind="attr:{for:name}"></label>
								<!-- /ko -->
								<!-- ko if:type == 'select' && typeof extra !== 'undefined' -->
									<!-- ko if:datatype == 'checkbox' || datatype == 'radio' -->
									<div class="ui form">
									  <div class="inline fields" data-bind="foreach: extra">
										  <div class="field">
											  <div class="ui checkbox" 
											     data-bind="css: { radio: $parent.datatype == 'radio' }">
											    <input data-bind="checked: $parent.value, attr:{type:$parent.datatype, name: $parent.name, id: 'radio-' + $parent.name + $index()}, value:value" class="hidden">
											    <label data-bind="text: key, attr:{ for: 'radio-' + $parent.name + $index()}"></label>
											  </div>
										  </div>
									  </div>
									</div>
									<!-- /ko -->
								<!-- /ko -->
								<i data-bind="visible:value.validationMessage()!='',text:value.validationMessage" class="validation-message"></i>
							</div>
						</td>
						<td>
							<i class="gray-text" data-bind="visible:value.validationMessage()=='',text:description"></i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	  </div>
  </main>
</section>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/aside-nav.js')
    ->js('/admin/js/index.js', '1.0.12');
?>

<script type="text/javascript">
(function(a){
    var path = document.getElementsByTagName('meta')['path'].content;
	//转换选项参数
	var transferParam = function(v){
		var arr, rules;
		if(v.rules.indexOf('|')>0){
			arr = v.rules.split('|');
			for(var i in arr){
				if(arr[i].indexOf('required')!=-1){
					v.required = true;
				}else if(arr[i].indexOf('=')!=-1){
					rules = arr[i].split('=');
					if(!v.pattern) v.pattern = {};
					v.pattern[rules[0]] = rules[1] || '';
				}else if('checkbox|number|date|radio|text|email|password'.indexOf(arr[i])!=-1){
					v.datatype = arr[i];
				}
			}
		}else{
			if(v.rules.indexOf('required')!=-1){
				v.required = true;
			}else if(v.rules.indexOf('=')!=-1){
				rules = v.rules.split('=');
				if(!v.pattern) v.pattern = {};
				v.pattern[rules[0]] = rules[1] || '';
			}else if('checkbox|number|date|radio|text|email|password'.indexOf(v.rules)!=-1){
				v.datatype = v.rules;
			}
		}
		v.value = ko.observable(v.value).extend({ required: ( v.required) ? v.required : false });
		return v;
	}

	function Model(){
		this.data = ko.observableArray([]);
		this.newItems = ko.observableArray([]);
		this.submit = function(){
			if(confirm('确定要提交吗？')){
				var data = ko.toJSON(this.data);
				console.log(this.data);
				a.http(path+'admin/console/config/update').data({data:data}).post(function(res){
					if(res.success) {
						window.location.reload();
					}else{
						alert(res.data);
					}
				})
			}
		}
		this.addItem = function(){
			var id = parseInt(this.data()[this.data().length-1].id)+1;
			var Item = function(id){
				var _this = this;
				this.id = id;
				this.alias = ko.observable('');
				this.name = ko.observable('');
				this.type = ko.observable();
				this.value = ko.observable('');
				this.datatype = ko.observable('');
				this.description = ko.observable('');
				this.required = ko.observable(false);
				this.optionTypes = [
					{text:'单行文本',value:'input'},
					{text:'多行文本',value:'textarea'},
					{text:'选择',value:'select'},
					{text:'开关',value:'switch'}
				];
				this.addExtra = function() {
					_this.optionRules().extra.push({
						key: ko.observable(''),
						value: ko.observable('')
					});
				}
				this.removeExtra = function(item) {
					_this.optionRules().extra.remove(item);
				}
				this.optionRules = ko.computed(function(){
					var options = {
						input:{
							options:[
								{text:'纯文本',value:'text'},
								{text:'数字',value:'number'},
								{text:'日期',value:'date'},
								{text:'邮箱',value:'email'},
								{text:'密码',value:'password'}
							]
						},
						select:{
							options:[
								{text:'单选框',value:'radio'},
								{text:'单选下拉框',value:'select'},
								{text:'复选框',value:'checkbox'},
								{text:'复选下拉框',value:'select[multiple]'}
							],
							extra:ko.observableArray([])
						},
						textarea:{
							options:[
								{text:'字数限制',value:'0'},
								{text:'文本框行数',value:'2'},
								{text:'附加选项',value:''}
							]
						},
						switch:{
							options:[
								{text:'布尔值',value:'boolean'}
							]
						}
					};
					return options[this.type()];
				},this);
				this.rules = ko.computed(function(){
					var rules = [];
					if(this.type() === 'textarea'){
						var opt = this.optionRules().options,
							maxLength = parseInt(opt[0].value),
							rows = parseInt(opt[1].value);
						if(maxLength){
							rules.push('maxLength='+maxLength);
						}
						if(rows){
							rules.push('rows='+rows);
						}
						if(this.required()){
							rules.push('required');
						}
					}else{
						rules.push(this.datatype());
						if(this.required()){
							rules.push('required');
						}
					}
					return rules.join('|');
				},this);
			};
			this.newItems.push(new Item(id));
		}
		this.addOptions = function(){
			var _this = this;
			var items = ko.toJS(this.newItems);
			items.forEach(function(v) {
				// 这里校验return 有问题
				if(v.name === '' || v.alias === '') return;
				// 还需要校验name是否唯一
				if(v.type === 'select') {
					v.extra = v.optionRules.extra;
				}
				delete v.optionRules;
				delete v.optionTypes;
				_this.data.push(transferParam(v));
			});
			this.newItems([]);
		}
		this.removeItem = function(data){
			this.newItems.remove(data);
		}
		this.removeItems = function(){
			this.newItems([]);
		}
	}
	var viewModel = new Model;
	ko.applyBindings(viewModel,document.getElementById('app'));
	
	$('#app .ui.dropdown').dropdown();

	ko.extenders.required = function(target, isRequired) {
		target.hasError = ko.observable();
		target.validationMessage = ko.observable("");
		//define a function to do validation
		function validate(newValue) {
			if(isRequired){
				target.hasError(newValue ? false : true);
				target.validationMessage(newValue ? "" : "请完成必填项");
			}
		}
		validate(target());
		target.subscribe(validate);
		return target;
	}
	
	ko.bindingHandlers.selectDropdown = {
	  init: function (element, valueAccessor, allBindings, viewModel) {
	      $(element).dropdown();
	  }
	};
	
	ko.bindingHandlers.popup = {
	  init: function (element, valueAccessor, allBindings, viewModel) {
		$(element).popup({
			popup : $(element).find('.meta-form'),
			inline: true,
			on    : 'click'
		})
	  }
	};

	a.http(path+'admin/console/config/options').post(function (res) {
		var data = res.data;
		data = data.map(function(v){
			if(v.extra) v.extra = JSON.parse('['+v.extra+']');
			return transferParam(v);
		});
		console.log(data)
		viewModel.data(data);
	})
})(new Ajax);
</script>
<?php $this->stop() ?>