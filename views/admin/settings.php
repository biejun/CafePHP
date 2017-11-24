<?php echo $this->tpl('start');?>

<?php echo $this->tpl('header');?>

<style>
input[type='radio'],
input[type='checkbox'] {
  display: none;
  cursor: pointer;
}
input[type='radio']:focus, input[type='radio']:active,
input[type='checkbox']:focus,
input[type='checkbox']:active {
  outline: none;
}
input[type='radio'] + label,
input[type='checkbox'] + label {
  cursor: pointer;
  display: inline-block;
  position: relative;
  padding-left: 25px;
  margin-right: 10px;
  color: #0b4c6a;
}
input[type='radio'] + label:before, input[type='radio'] + label:after,
input[type='checkbox'] + label:before,
input[type='checkbox'] + label:after {
  content: '';
  font-family: helvetica;
  display: inline-block;
  width: 18px;
  height: 18px;
  left: 0;
  bottom: 0;
  text-align: center;
  position: absolute;
}
input[type='radio'] + label:before,
input[type='checkbox'] + label:before {
  background-color: #fafafa;
  -moz-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
input[type='radio'] + label:after,
input[type='checkbox'] + label:after {
  color: #fff;
}
input[type='radio']:checked + label:before,
input[type='checkbox']:checked + label:before {
  -moz-box-shadow: inset 0 0 0 10px #158EC6;
  -webkit-box-shadow: inset 0 0 0 10px #158EC6;
  box-shadow: inset 0 0 0 10px #158EC6;
}

/*Radio Specific styles*/
input[type='radio'] + label:before {
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
}
input[type='radio'] + label:hover:after, input[type='radio']:checked + label:after {
  content: '\2022';
  position: absolute;
  top: 0px;
  font-size: 19px;
  line-height: 15px;
}
input[type='radio'] + label:hover:after {
  color: #c7c7c7;
}
input[type='radio']:checked + label:after, input[type='radio']:checked + label:hover:after {
  color: #fff;
}

/*Checkbox Specific styles*/
input[type='checkbox'] + label:before {
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}
input[type='checkbox'] + label:hover:after, input[type='checkbox']:checked + label:after {
  content: "\2713";
  line-height: 18px;
  font-size: 14px;
}
input[type='checkbox'] + label:hover:after {
  color: #c7c7c7;
}
input[type='checkbox']:checked + label:after, input[type='checkbox']:checked + label:hover:after {
  color: #fff;
}

/*Toggle Specific styles*/
input[type='checkbox'].toggle {
  display: inline-block;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 55px;
  height: 28px;
  background-color: #fafafa;
  position: relative;
  -moz-border-radius: 30px;
  -webkit-border-radius: 30px;
  border-radius: 30px;
  @inlcude box-shadow(none);
  -moz-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
input[type='checkbox'].toggle:hover:after {
  background-color: #c7c7c7;
}
input[type='checkbox'].toggle:after {
  content: '';
  display: inline-block;
  position: absolute;
  width: 24px;
  height: 24px;
  background-color: #adadad;
  top: 2px;
  left: 2px;
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
  -moz-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
input[type='checkbox']:checked.toggle {
  -moz-box-shadow: inset 0 0 0 15px #158EC6;
  -webkit-box-shadow: inset 0 0 0 15px #158EC6;
  box-shadow: inset 0 0 0 15px #158EC6;
}
input[type='checkbox']:checked.toggle:after {
  left: 29px;
  background-color: #fff;
}
</style>

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
								<label for=""></label>
								<input type="radio" class="magic-radio">
<input id='check-1' type="checkbox" name='check-1' checked='checked' />
<label for="check-1">Apples</label>

<input id='check-2' type="checkbox" name='check-1' />
<label for="check-2">Oranges</label>
<input id='radio-1' type="radio" name='r-group-1' checked='checked' />
<label for="radio-1">Day</label>

<input id='radio-2' type="radio" name='r-group-1' />
<label for="radio-2">Night</label>
<input class='toggle' type="checkbox" name='check-3' checked='checked' />
<input class='toggle' type="checkbox" name='check-4' />
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