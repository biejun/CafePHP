<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<section class="page-container" id="app">

  <?php echo $this->tpl('header');?>
  
  <main class="page-main">
	  <div class="ui two column centered grid">
	    <div class="column">
			<form class="ui form mt-15">
				<div class="field">
					<label>用户角色</label>
					  <div>
						  <?php if ($data['is_admin'] && $data['level'] == 10) {?>
							超级管理员
						  <?php } elseif ($data['is_admin']) { ?>
							管理员
						  <?php } else {?>
							普通用户
						  <?php }?>
					  </div>
				</div>
				<div class="field">
					<label>邮箱</label>
					<input type="text" value="<?php echo $data['email'];?>">
				</div>
				<div class="field">
					<label>头像</label>
					<input type="file" name="file" >
				</div>
				<div class="field">
					<label>签名</label>
					<textarea name="description" value="<?php echo $data['description'];?>"></textarea>
				</div>
				<button class="ui primary button" type="button">更新信息</button>
			</form>
		</div>
	  </div>

  </main>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/scripts-page');?>
<?php echo $this->tpl('tpl/end');?>
