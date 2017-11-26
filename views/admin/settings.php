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
						<th data-bind="text:alias" width="220" align="right"></th>
						<td width="120">
							<span class="item-name" data-bind="text:name"></span>
						</td>
						<td width="500" class="form-group">
							<!-- ko if:type == 'text' -->
								<input type="text" data-bind="value:value" class="form-control">
							<!-- /ko -->
							<!-- ko if:type == 'password' -->
								<input type="password" data-bind="value:value" class="form-control">
							<!-- /ko -->
							<!-- ko if:type == 'email' -->
								<input type="email" data-bind="value:value" class="form-control">
							<!-- /ko -->
							<!-- ko if:type == 'number' -->
								<input type="number" data-bind="value:value" class="form-control">
							<!-- /ko -->
							<!-- ko if:type == 'bigtext' -->
								<textarea rows="4" data-bind="value:value" class="form-control"></textarea>
							<!-- /ko -->
							<!-- ko if:type == 'radio' -->
<input id='check-1' type="checkbox" name='check-1' checked='checked' />
<label for="check-1">Apples</label>

<input id='check-2' type="checkbox" name='check-1' />
<label for="check-2">Oranges</label>
<input id='radio-1' type="radio" name='r-group-1' checked='checked' />
<label for="radio-1">Day</label>

<input id='radio-2' type="radio" name='r-group-1' />
<label for="radio-2">Night</label>
<input class='switch' type="checkbox" name='check-3' checked='checked' />
<input class='switch' type="checkbox" name='check-4' />
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

	a.post('/admin/api/settings',null,function (res) {
		console.log(res.data)
		viewModel.data(res.data);
	})
</script>

<?php echo $this->tpl('end');?>