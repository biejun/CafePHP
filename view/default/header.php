<div class="header">
    <div class="header__left">
        <a href="<?=$this->u()?>"
           title="<?=$this->options('title')?>" 
           class="site-logo">
            <span>LOGO</span>
        </a>
        <?php if(!isset($hideSearch)) : ?>
        <div class="ui search">
          <div class="ui icon input">
            <input class="prompt" type="text" placeholder="搜索">
            <i class="search icon iconfont icon-search"></i>
          </div>
          <div class="results"></div>
        </div>
        <?php endif; ?>
        <?php if(!isset($hideMenu)) : ?>
        <ul class="site-nav-menu">
            <li class="site-nav-menu-item <?=$active === 'index' ? 'active' : ''?>">
                <a href="<?=$this->u()?>">首页</a>
            </li>
            <li class="site-nav-menu-item <?=$active === 'help' ? 'active' : ''?>">
                <a href="<?=$this->u('help')?>">帮助</a>
            </li>
        </ul>
        <?php endif; ?>
    </div>
    
    <?php if($this->account()->isLogin()) : ?>
        <div id="site-user" class="site-user-message">
            <a href="<?=$this->u('notification/unread')?>" class="notification">
               <i class="iconfont icon-notification"></i>
            </a>
            <div class="ui dropdown">
              <div class="text">
                  <img class="avatar" src="<?=$this->u($myMeta->avatar)?>" alt="">
              </div>
              <i class="iconfont icon-unfold"></i>
                <div class="menu">
                  <a class="item">
                      我的主页
                  </a>
                  <a class="item">
                      设置
                  </a>
                  <a class="item" href="<?=$this->u('logout')?>">
                      退出登录
                  </a>
                </div>
            </div>
        </div>
    <?php endif;?>
    
    <?php if (!$this->account()->isLogin()): ?>
        <div class="site-user-sign">
            <a class="ui tiny button login" href="<?=$this->u('login')?>">
              登录
            </a>
            <a class="ui tiny primary button br-8" href="<?=$this->u('register')?>">
              注册账号
            </a>
        </div>
    <?php endif; ?>
</div>