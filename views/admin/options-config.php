<?php echo $this->tpl('start');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container">
		<div class="main-panel">
			<div class="fr mt-5">
				<button type="submit" class="ribbon-button" role="button"><i class="icon icon-plus-circled"></i>自定义配置项</button>
			</div>
			<h2>设置</h2>
		</div>
		<div class="settings" path="<?php echo $this->path;?>admin/update/setting" method="post">
			<table class="s-table" cellpadding="0" cellspacing="0">
				<tbody data-bind="foreach:data">
					<tr>
						<th data-bind="text:alias" width="180" align="right"></th>
						<td width="150">
							<span class="item-name" data-bind="text:name"></span>
						</td>
						<td width="500" class="form-group">
							<!-- ko if:type == 'input' -->
								<!-- ko if:datatype != 'checkbox' || datatype != 'radio' -->
								<input data-bind="value:value,attr:{type:datatype}" class="form-control">
								<!-- /ko -->
								<!-- ko if:datatype == 'checkbox' || datatype == 'radio' -->
								<input data-bind="value:value,attr:{type:datatype}">
								<label data-bind="attr:{for:name}"></label>
								<!-- /ko -->
							<!-- /ko -->
							<!-- ko if:type == 'textarea' -->
								<textarea data-bind="value:value,attr:pattern" class="form-control"></textarea>
							<!-- /ko -->
							<!-- ko if:type == 'switch' -->
								<input class='switch' type="checkbox" data-bind="checked:value" />
							<!-- /ko -->
							<span class="item-name" data-bind="text:description"></span>
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
	var a = new Ajax;
	var Model = function(){
		this.data = ko.observableArray([]);
		this.submit = function(){
			console.log(this.data())
		}
	}
	var viewModel = new Model;
	ko.applyBindings(viewModel,document.getElementById('app'));

	a.post('/admin/api/options',null,function (res) {
		var data = res.data, arr, rules;
		data.map(function(v){
			v.value = ko.observable(v.value);
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
		});
		viewModel.data(data);
	})
</script>

<?php echo $this->tpl('end');?>