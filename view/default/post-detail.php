<?php $this->layout('common::layout') ?>

<?php $this->start('styles') ?>
<?php
  // 合并编辑页面样式
  $this->compress()
    ->add('/css/comment-editor.css')
    ->add('/css/simditor-emoji.css')
    ->add('/css/simditor-mention.css')
    ->css('default/css/comment-reply.css', '1.0.12');
?>
<?php
  $this->compress(__DIR__)
    ->add('/css/app.css')
    ->css('/default/css/post-detail.css', '1.0.12');
?>
<?php $this->stop() ?>

<div class="app-layout">
    <div class="app-header">
        <?php $this->insert('header')?>
    </div>
    
    <div class="app-main">
        <div class="content article">
            <?php if($post->post_title) : ?>
            <h1 class="article-title"><?=$post->post_title?></h1>
            <?php endif;?>
            <div class="article-info">
                <a href="<?=$post->user_url?>" class="data-userName">
                    <img src="<?=$this->u($post->avatar)?>" alt="<?=$post->user_name?>" class="data-userAvatar">
                    <span class="data-name">
                        <?=$post->nickname?>
                    </span>
                </a>
               <span class="data-time">
                   发布于 <time datetime="2021-02-19T18:18:42+08:00"><?=$post->post_time?></time>
               </span>
               <span class="data-statistic">浏览 <?=$post->read_count?></span>
               <a href="<?=$this->u('post/edit?type='.$post->post_type.'&id='.$post->post_id)?>" title="编辑" class="data-edit" rel="nofollow">编辑</a>
            </div>
            <div class="article-content">
                <?=$post->post_content?>
            </div>
            <div class="article-comment">
                <div class="comment-bigTitle">
                    <div class="title">说说我的看法</div>
                </div>
                <div id="comment-form" class="comment-form">
                    <textarea data-bind="editor: { value: comment_content }"></textarea>
                    <div class="ui tiny submit primary button comment-btn" data-bind="click: submit">发布</div>
                </div>
                <div class="comment-bigTitle">
                    <div class="title">全部评论</div>
                </div>
                <div class="comment-list">
        
                    <?php foreach($comments as $row) : ?>
                    <div class="comment-item" data-comment-id="<?=$row->comment_id?>">
                        <div class="comment-heading">
                            <div class="comment-avatar">
                                <img src="<?=$this->u($row->avatar)?>" alt="">
                            </div>
                            <div class="comment-nickname">
                                <?=$row->nickname?>
                            </div>
                        </div>
                        <div class="comment-content" id="comment-ko-<?=$row->comment_id?>">
                            <section>
                                <div class="comment-contentText">
                                    <?=$row->comment_content?>
                                </div>
                            </section>
                            <div class="comment-bottom">
                                <div class="comment-time"><?=$row->comment_time?></div>
                                <div class="comment-actions">
                                    <button type="button" class="comment-operate" data-bind="click: showEditor">
                                        <i class="iconfont icon-pinglun"></i>
                                        <span data-bind="text: statusText">回复</span>
                                    </button>
                                    <button type="button" class="comment-operate" data-bind="click: showEditor">
                                        <i class="iconfont icon-dianzan"></i>
                                        <span>点赞</span>
                                    </button>
                                </div>
                            </div>
                            <!-- ko if:visible() -->
                            <div class="comment-form">
                                <textarea data-bind="editor: { value: content }"></textarea>
                                <div class="ui tiny submit primary button comment-btn" data-bind="click: submit">发布</div>
                            </div>
                            <!-- /ko -->
                        </div>
                        <?php if(isset($row->replies)) : ?>
                        <div class="subComment-list">
                            <?php foreach($row->replies as $row2): ?>
                            <div class="subComment-item" data-reply-id="<?=$row2->reply_id?>" data-reply-uid="<?=$row2->uid?>">
                                <div class="comment-heading">
                                    <div class="comment-avatar">
                                        <img src="<?=$this->u($row2->avatar)?>" alt="">
                                    </div>
                                    <div class="comment-nickname">
                                        <?=$row2->nickname?>
                                    </div>
                                    <?php if($row2->to_reply_id) :?>
                                    <span class="comment-reply">回复</span>
                                    <div class="comment-nickname">
                                        <?=$row2->reply_nickname?>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="comment-content" id="reply-ko-<?=$row2->reply_id?>">
                                    <section>
                                        <div class="comment-contentText">
                                            <?=$row2->reply_content?>
                                        </div>
                                    </section>
                                    <div class="comment-bottom">
                                        <div class="comment-reply-actions">
                                            <div class="comment-time"><?=$row2->reply_time?></div>
                                            <button type="button" class="comment-operate" data-bind="click: showEditor">
                                                <i class="iconfont icon-pinglun"></i>
                                                <span data-bind="text: statusText">回复</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- ko if:visible() -->
                                    <div class="comment-form">
                                        <textarea data-bind="editor: { value: content }"></textarea>
                                        <div class="ui tiny submit primary button comment-btn" data-bind="click: submit">发布</div>
                                    </div>
                                    <!-- /ko -->
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <?php endif;?>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scripts') ?>

<script src="<?=$this->u('@src/js/simditor/module.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/hotkeys.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/dompurify.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/simditor-emoji.js');?>"></script>
<script src="<?=$this->u('@src/js/simditor/simditor-mention.js');?>"></script>

<script>
    window.__comment_conf__ = {
        path: "<?=$this->u();?>",
        emojiPath: "<?=$this->u('@src/img/emoji/');?>",
        post_id: <?=$post->post_id?>
    }
</script>

<?php
  $this->compress(__DIR__)
    ->add('/js/post-comment.js')
    ->js('/default/js/post-comment.js', '1.0.12');
?>
<?php $this->stop() ?>