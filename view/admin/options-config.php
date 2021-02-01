<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="fr mt-5">
				<button type="button" class="ribbon-button" role="button" data-bind="click:addItem">
					<i class="icon icon-plus-circled"></i>自定义配置项
				</button>
			</div>
			<h2>设置</h2>
		</div>
		<div class="settings">
			<table class="s-table text-center" cellpadding="0" cellspacing="0" data-bind="if:newItems().length>0">
				<thead>
					<tr>
						<th>移除</th>
						<th><span>*</span>配置项名称</th>
						<th><span>*</span>配置项英文</th>
						<th>配置项类型</th>
						<th width="300" colspan="2">配置项校验规则</th>
						<th>配置项默认值</th>
						<th>必填项</th>
					</tr>
				</thead>
				<tbody data-bind="foreach:newItems">
					<tr>
						<td class="cell">
							<button type="button" class="removeItem" data-bind="click:$root.removeItem.bind($root,$data)">
								<i class="icon icon-minus-circled"></i>
							</button>
						</td>
						<td class="cell">
							<input type="text" data-bind="value:alias" class="form-control"/>
						</td>
						<td class="cell">
							<input type="text" data-bind="value:name" class="form-control"/>
						</td>
						<td class="cell">
							<select data-bind="options:optionTypes,
												value:type,
												optionsText:'text',
												optionsValue:'value'" class="form-control">
							</select>
						</td>
						<td width="300" colspan="2" class="s-child">
							<table class="s-child-table" cellpadding="0" cellspacing="0" >
								<!-- ko if:type() != 'textarea'-->
								<thead>
									<tr>
										<th style="width:50%">数据类型</th>
										<th style="width:50%">附加选项</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="width:50%">
											<select data-bind="options:optionRules().options,
																optionsText:'text',
																value:datatype,
																disable:type() === 'switch',
																optionsValue:'value'" class="form-control"></select>
										</td>
										<td style="width:50%">
											<a href="">配置描述</a>
											<!--ko if:optionRules().extra-->
												<span>|</span>
												<a href="">选择项</a>
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
											<input type="number" data-bind="value:value" class="form-control" style="width:100%" />
											<!-- /ko -->
											<!-- ko if:value===''-->
											<a href="">配置描述</a>
											<!-- /ko -->
										</td>
									</tr>
								</tbody>
								<!-- /ko -->
							</table>							
						</td>
						<td class="cell">
							<input type="text" data-bind="value:value" class="form-control"/>
						</td>
						<td class="cell">
							<input type="checkbox" data-bind="attr:{'id':'isRequire-'+id},checked:required" class="checkbox"/>
							<label data-bind="attr:{'for':'isRequire-'+id}">&nbsp;</label>	
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="8" class="text-center">
							<button type="submit" class="btn btn-primary mt-10" data-bind="click:addOptions,text:'添加'"></button>
							<button type="submit" class="btn mt-10 ml-10" data-bind="click:removeItems,text:'取消'"></button>
						</td>
					</tr>
				</tfoot>
			</table>
			<table class="s-table" cellpadding="0" cellspacing="0" data-bind="if:newItems().length===0">
				<tbody data-bind="foreach:data">
					<tr>
						<th data-bind="text:alias" width="180" align="right"></th>
						<td width="150">
							<span class="item-name" data-bind="text:name"></span>
						</td>
						<td width="600">
							<div data-bind="visible:$parent.data" class="form-group" style="display:none;">
								<!-- ko if:type == 'input' -->
									<!-- ko if:datatype != 'checkbox' || datatype != 'radio' -->
									<input data-bind="value:value,
													attr:{type:datatype},
													css:{ error: value.hasError }" class="form-control">
									<!-- /ko -->
									<!-- ko if:datatype == 'checkbox' || datatype == 'radio' -->
									<input data-bind="value:value,attr:{type:datatype,id:name}">
									<label data-bind="attr:{for:name}"></label>
									<!-- /ko -->
								<!-- /ko -->
								<!-- ko if:type == 'textarea' -->
									<textarea data-bind="value:value,attr:pattern,css:{ error: value.hasError }" class="form-control"></textarea>
								<!-- /ko -->
								<!-- ko if:type == 'switch' -->
									<input class="switch" type="checkbox" data-bind="checked:value,attr:{'id':name},value:value" />
									<label data-bind="attr:{for:name}"></label>
								<!-- /ko -->
								<i data-bind="visible:value.validationMessage()!='',text:value.validationMessage" class="validation-message"></i>
								<i class="item-name" data-bind="visible:value.validationMessage()=='',text:description"></i>
							</div>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td>
							<button type="submit" class="btn btn-primary mt-10" data-bind="click:submit,text:'提交'"></button>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/header-scripts');?>

<script type="text/javascript">
(function(a,path){

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
				a.http(path+'admin/options/update').data({data:data}).post(function(res){
					window.location.reload();
				})
			}
		}
		this.addItem = function(){
			var id = parseInt(this.data()[this.data().length-1].id)+1;
			var Item = function(id){
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
				if(v.name === '' || v.alias === '') return;
				_this.data.push(transferParam(v));
			});
			this.newItems([]);
			console.log(items)
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

	a.http(path+'admin/api/options').post(function (res) {
		var data = res.data;
		data = data.map(function(v){
			return transferParam(v);
		});
		viewModel.data(data);
	})
})(new Ajax,_CONFIG_.path);
</script>

<?php echo $this->tpl('tpl/end');?>