<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/app.css')
    ->add('/css/dataFlow.css')
    ->add('/css/topic.css')
    ->add('/css/side.css')
    ->css('/default/css/index.css', '1.0.12');
?>
<?php $this->stop() ?>

<div class="app-layout">
    <div class="app-header">
        <?php $this->insert('header', ['active' => 'index'])?>
    </div>
    <div class="app-main">
        <div class="home-container">
            <h1>妙计，记录旅行、待办和想法</h1>
            <div class="ui form">
              <div class="inline fields">
                <div class="field">
                    <div class="ui labeled input">
                        <label for="domain" class="ui basic label"><?=$domain?>/</label>
                        <input type="text" placeholder="字母、数字及下划线" id="amount">
                    </div>
                </div>
                <div class="field">
                  <div class="ui button primary">创建我的个人主页</div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
