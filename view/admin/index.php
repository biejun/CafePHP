<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  $this->compress(__DIR__)
    ->add('/css/layout.css')
    ->css('/admin/css/index.css', '1.0.12');
?>
<?php $this->stop() ?>

<section class="page-container">

  <?php $this->insert('header')?>
  
  <main class="page-main">
	  fsdfsdf
  </main>
</section>

<?php $this->start('scripts') ?>
<?php
  $this->compress(__DIR__)
    ->add('/js/aside-nav.js')
    ->js('/admin/js/index.js', '1.0.12');
?>
<?php $this->stop() ?>