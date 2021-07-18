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
        <?php $this->insert('header', ['active' => 'help'])?>
    </div>
    <div class="app-main">

    </div>
</div>