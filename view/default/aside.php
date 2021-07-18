<div class="dashboard-aside">
    <div class="aside-box user-profile">
        <a href="" class="user-avatar">
            <img src="<?=$this->u($myMeta->avatar)?>" alt="">
        </a>
        <h4 class="user-nickname"><?=$myMeta->nickname?></h4>
        <p class="user-description">
            <?=$myMeta->description?>
        </p>
        <div class="user-public">
            <button id="create" class="tiny fluid ui primary button">
                <i class="iconfont icon-add"></i>
              新建
            </button>
        </div>
    </div>
    <div class="aside-box">
        <ul class="aside-menu">
            <li><a href="<?=$this->u('dashboard/discover')?>" class="aside-menu-item active"><i class="iconfont icon-discover"></i>发现</a></li>
            <li><a href="<?=$this->u('dashboard/post')?>" class="aside-menu-item"><i class="iconfont icon-list"></i>我的</a></li>
        </ul>
        <ul class="aside-menu">
            <li><a href="<?=$this->u('dashboard')?>" class="aside-menu-item"><i class="iconfont icon-cascades"></i>工作台</a></li>
            <li><a href="<?=$this->u($myMeta->name."/follow")?>" class="aside-menu-item"><i class="iconfont icon-friendfavor"></i>关注</a></li>
            <li><a href="<?=$this->u("settings")?>" class="aside-menu-item"><i class="iconfont icon-settings"></i>设置</a></li>
        </ul>
    </div>
</div>

<div class="ui modal create-post-modal">
    <div class="header">请选择一个类型</div>
  <div class="content">
    <div class="content-template">
        <a href="<?=$this->u('post/create?type=3')?>" class="template note-template">
            <h5>笔记</h5>
            <div class="template-desc">
                <p>分享好物、美妆、护肤、健身、旅行等有用经验</p>
            </div>
            <div class="template-entry">撰写<i class="iconfont icon-enter"></i></div>
        </a>
        <a href="<?=$this->u('post/create?type=1')?>" class="template mood-template">
            <h5>说点什么</h5>
            <div class="template-desc">
                <p>记录一下今天的心情</p>
            </div>
            <div class="template-entry">开始记录<i class="iconfont icon-enter"></i></div>
        </a>
        <a href="<?=$this->u('post/create?type=2')?>" class="template task-template">
            <h5>待办事项</h5>
            <div class="template-desc">
                <p>创建你的待办任务或购物清单</p>
            </div>
            <div class="template-entry">开始创建<i class="iconfont icon-enter"></i></div>
        </a>
    </div>
  </div>
</div>