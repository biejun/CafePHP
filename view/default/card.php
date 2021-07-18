<div class="app-dataCard<?= $item->post_type !== '1' ? ' data-article':'';?>">
    <?php if($item->post_type !== '1'):?>
    <h3><a href="<?=$item->post_url?>"><?=$item->post_title?></a></h3>
    <?php endif;?>
    <div class="data-heading">
        <a href="<?=$item->user_url?>" class="data-userName">
            <img src="<?=$item->avatar?>" alt="<?=$item->user_name?>" class="data-userAvatar">
            <span class="data-name">
                <?=$item->nickname?>
            </span>
        </a>
        <div class="data-options">
            <i class="iconfont icon-switch"></i>
        </div>
    </div>
    <div class="data-content">
         <?=$item->post_desc?>
    </div>
    
    <?php if($item->post_image_thumbs) : ?>
    
    <div class="data-images image-count-<?=$item->post_image_count?>">
    
        <?php foreach($item->post_image_thumbs as $image) :?>
        <div class="data-image">
            <img src="<?=$image->data?>" alt="">
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif;?>
    
    <div class="data-footer">
        <span class="data-time">
            <time datetime="2021-02-19T18:18:42+08:00"><?=$item->post_time?></time>
        </span>
        <div class="data-link">
            <a href="<?=$item->post_url?>">评论</a>
        </div>
    </div>
</div>