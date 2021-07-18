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
    <div class="app-header is-fixed">
        <?php $this->insert('header', [
            'hideMenu' => true,
            'hideSearch' => true
        ])?>
    </div>
    <div class="app-main">
        <div class="content dashboard">
            
            <?php $this->insert('aside')?>
            
            <div class="dashboard-wrapper">
                                
                <div class="dashboard-main">
                    <div class="app-dataFlow">
                        <?php foreach($posts as $item) : ?>
                           <?php $this->insert('card', ['item' => $item]);?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="dashboard-sidebar">
                    <div class="aside-recBox">
                        <div class="aside-recBox-heading">
                            <h3 class="aside-recBox-title">
                                话题
                            </h3>
                            <a href="<?=$this->u('topic/all')?>" class="aside-recBox-more">全部</a>
                        </div>
                        <div class="aside-recContent">
                            <?php foreach($recommTopics as $row) : ?>
                            <a href="<?=$this->u('topic/'.$row->topic_id)?>" class="topic-item">
                                <div>
                                    <h4 class="topic-title">
                                        <i class="iconfont icon-huati topic-icon"></i>
                                        <?=$row->topic_name?>
                                    </h4>
                                    <div class="topic-desc"><?=$row->topic_desc?></div>
                                </div>
                                <?php if(!empty($row->topic_cover)) : ?>
                                <div class="topic-coverPic">
                                    <img src="<?=$row->topic_cover?>" alt="">
                                </div>
                                <?php endif;?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scripts') ?>
<script>
    $('#create').click(function() {
        $('.ui.modal')
          .modal('show')
        ;
    });
</script>
<?php $this->stop() ?>