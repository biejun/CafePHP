<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<?php echo $this->tpl('header');?>

<section class="page-main" id="app" role="main">
	<div class="container">
		<div class="main-panel">
			<h2>个人资料</h2>
		</div>
    <div class="settings">
      <table class="s-table" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <th data-bind="text:alias" width="180" align="right"></th>
            <td width="150">
              <span class="item-name">
                用户角色
              </span>
            </td>
            <td width="600">
              <div class="form-group">
                <?php if($data['is_admin'] && $data['level'] == 10) {?>
                  超级管理员
                <?php }else if($data['is_admin']){ ?>
                  管理员
                <?php }else{?>
                  普通用户
                <?php }?>
              </div>
            </td>
          </tr>
          <tr>
            <th data-bind="text:alias" width="180" align="right"></th>
            <td width="150">
              <span class="item-name">
                用户邮箱
              </span>
            </td>
            <td width="600">
              <div class="form-group">
                <input type="text" class="form-control" value="<?php echo $data['email'];?>">
              </div>
            </td>
          </tr>
          <tr>
            <th data-bind="text:alias" width="180" align="right"></th>
            <td width="150">
              <span class="item-name">
                用户头像
              </span>
            </td>
            <td width="600">
              <div class="form-group">
                <input type="file" name="file" >
              </div>
            </td>
          </tr>
          <tr>
            <th data-bind="text:alias" width="180" align="right"></th>
            <td width="150">
              <span class="item-name">
                用户签名
              </span>
            </td>
            <td width="600">
              <div class="form-group">
                <textarea name="description" value="<?php $data['description'];?>" class="form-control">
                </textarea>
              </div>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td></td>
            <td></td>
            <td>
              <button type="submit" class="btn btn-primary mt-10">保存修改</button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
	</div>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/header-scripts');?>

<?php echo $this->tpl('tpl/end');?>