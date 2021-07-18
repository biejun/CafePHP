<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  // 合并编辑器插件样式
  $this->compress()
    ->add('/css/simditor.css')
    ->add('/css/simditor-emoji.css')
    ->add('/css/simditor-checklist.css')
    ->add('/css/simditor-mention.css')
    ->css('/default/css/simditor.css', '1.0.1');
  
  // 合并编辑页面样式
  $this->compress(__DIR__)
    ->add('/css/app.css')
    ->add('/css/write.css')
    ->css('/default/css/write.css', '1.0.12');
?>
<?php $this->stop() ?>

<div class="app-layout">
    <div class="app-header">
        <?php $this->insert('header', [
            'active' => 'index',
            'hideMenu' => true,
            'hideSearch' => true
        ])?>
    </div>
    <div class="app-main">
        <div class="content write-page">
            <div class="write-wrapper">
                <div class="write-left write-setting">
                    <h3>内容设置</h3>
                    <div class="ui mini form">
                      <?php if ($type !== '1') : ?>
                      <div class="field mb-20">
                        <label>标题</label>
                        <div class="write-title">
                          <div class="write-titleLimit"></div>
                          <input type="text" id="write-title" class="write-title-input" placeholder="请输入标题"/>
                        </div>
                      </div>
                      <?php endif; ?>
                      <div class="field mb-20">
                        <label>权限设置</label>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_privacy" value="0" checked="checked" tabindex="0" class="hidden">
                            <label>所有人可见</label>
                          </div>
                        </div>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_privacy" value="1" tabindex="0" class="hidden">
                            <label>仅关注我的人可见</label>
                          </div>
                        </div>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_privacy" value="2" tabindex="0" class="hidden">
                            <label>仅自己可见</label>
                          </div>
                        </div>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_privacy" value="3" tabindex="0" class="hidden">
                            <label>密码访问</label>
                          </div>
                        </div>
                      </div>
                      <div class="field mb-20" hidden>
                        <label>设置密码</label>
                        <input type="text" name="post_pass" placeholder="请设置查看密码">
                        <span class="field-help">
                            <i class="iconfont icon-questionfill"></i>
                            我们将会对您的内容进行加密存储
                        </span>
                      </div>
                      <div class="field remote filter topic mb-20">
                        <label style="vertical-align: -7px;">添加话题</label>
                        <div class="ui multiple search selection dropdown" style="width: 340px;">
                          <input type="hidden" name="post_topics" value="">
                          <i class="dropdown icon"></i>
                          <input class="search">
                          <div class="default text">输入话题名称</div>
                          <div class="menu">
                          </div>
                        </div>
                      </div>
                      <div class="field mb-20">
                        <label>评论设置</label>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_lock" checked="checked" value="0" class="hidden">
                            <label>允许评论</label>
                          </div>
                        </div>
                        <div class="field">
                          <div class="ui radio checkbox">
                            <input type="radio" name="post_lock" value="1" class="hidden">
                            <label>关闭评论</label>
                          </div>
                        </div>
                      </div>
                      <button id="submit-post" class="ui mini primary button" type="submit">发布</button>
                      <span id="draft-msg" class="post-draft-msg"></span>
                    </div>
                </div>
                <div class="write-right">
                    <textarea id="editor" hidden></textarea>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->start('scripts') ?>

<script src="<?=$this->u('@src/js/image-compressor.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/module.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/uploader.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/hotkeys.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/dompurify.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/simditor-emoji.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/simditor-checklist.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/simditor-mention.js');?>"></script>

<script>
    window.__write_conf__ = {
        path: "<?=$this->u();?>",
        type: <?= $type;?>,
        csrf: "<?= $csrf;?>",
        <?php if ($post) : ?>
        post_id: <?=$post['post_id'];?>,
        post: <?=json_encode($post);?>,
        <?php endif;?>
        emojiPath: "<?=$this->u('@src/img/emoji/');?>"
    }
</script>

<?php
  $this->compress(__DIR__)
    ->add('/js/post-write.js')
    ->js('/default/js/post-write.js', '1.0.12');
?>
<?php $this->stop() ?>