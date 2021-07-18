<!doctype html>
<html lang="<?=$this->lang()?>">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <link rel="icon" href="<?=$this->u('favicon.ico');?>" type="image/x-icon"/>
    <?php
       $this->compress()
         ->add('/css/common.css')
         ->add('/css/semantic.min.css')
         ->add('/icon/iconfont.css')
         ->css('/css/common.css', '1.0.1');
    ?>
    <?=$this->section('styles')?>
    <meta name="path" content="<?=$this->u()?>">
    <?php if(isset($title)): ?>
    <title><?=$title?> - <?=$this->options('title')?></title>
    <?php else : ?>
    <title><?=$this->options('title')?></title>
    <?php endif?>
    <!--[if lt IE 9]>
    <script src="<?=$this->u('@src/js/html5shiv.js');?>"></script>
    <![endif]-->
</head>
<body>
    
    <?=$this->section('content')?>
    
    <?php
      $this->compress()
        ->add('/js/jquery-3.5.1.min.js')
        ->add('/js/semantic.min.js')
        ->add('/js/knockout-3.4.2.js')
        ->add('/js/md5.js')
        ->add('/js/vendor.js')
        ->js('/js/chunk-common.js', '1.2');
    ?>
    <?=$this->section('scripts')?>
    
</body>
</html>