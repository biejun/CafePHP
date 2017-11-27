<?php echo $this->tpl('start');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container" data-bind="visible:data" style="display:none;">
		<div class="main-panel">
			<div class="fr mt-5">
				<button type="submit" class="ribbon-button" role="button"><i class="icon icon-plus-circled"></i>自定义配置项</button>
			</div>
			<h2>设置</h2>
		</div>
		<div class="settings">
			<table class="s-table" cellpadding="0" cellspacing="0">
				<tbody data-bind="foreach:data">
					<tr>
						<th data-bind="text:alias" width="180" align="right"></th>
						<td width="150">
							<span class="item-name" data-bind="text:name"></span>
						</td>
						<td width="600" class="form-group">
							<!-- ko if:type == 'input' -->
								<!-- ko if:datatype != 'checkbox' || datatype != 'radio' -->
								<input data-bind="value:value,attr:{type:datatype},css:{ error: value.hasError }" class="form-control">
								<!-- /ko -->
								<!-- ko if:datatype == 'checkbox' || datatype == 'radio' -->
								<input data-bind="value:value,attr:{type:datatype}">
								<label data-bind="attr:{for:name}"></label>
								<!-- /ko -->
							<!-- /ko -->
							<!-- ko if:type == 'textarea' -->
								<textarea data-bind="value:value,attr:pattern,css:{ error: value.hasError }" class="form-control"></textarea>
							<!-- /ko -->
							<!-- ko if:type == 'switch' -->
								<input class='switch' type="checkbox" data-bind="checked:value" />
							<!-- /ko -->
							<i data-bind="visible:value.validationMessage()!='',text:value.validationMessage" class="validation-message"></i>
							<i class="item-name" data-bind="visible:value.validationMessage()=='',text:description"></i>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td>
							<button type="submit" class="s-button mt-10" data-bind="click:submit">提交</button>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</section>

<?php echo $this->tpl('scripts');?>

<script type="text/javascript">
	var a = new Ajax, path = _CONFIG_.path;
	var Model = function(){
		this.data = ko.observableArray([]);
		this.action = path+"admin/update/setting";
		this.submit = function(){
			console.log(this.data())
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

	a.post(path+'admin/api/options',null,function (res) {
		var data = res.data, arr, rules;
		data.map(function(v){
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
		});
		viewModel.data(data);
	})
</script>

<?php echo $this->tpl('end');?>