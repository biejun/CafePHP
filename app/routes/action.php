<?php
/* 路由中间件介绍 */

/* 路由器初始化时挂载的动作 */
$action->add('route:init', function () {
    /* 判断系统是否已安装 */

});
/* 路由请求响应前挂载的动作 */
$action->add('route:before', function () {
});

/* 路由请求响应后挂载的动作 */
$action->add('route:after', function () {
});

/* 路由请求响应失败后挂载的动作 */
$action->add('route:failed', function () {
});
/* 检查安装 */
$action->add('check:install', function () {
    if (!$this->app->existLock()) {
        $this->response->redirect(u('install/index#step-one'));
    }
});

// 我的个人信息（头像、昵称）
$action->add('my:meta', function () {
    $account = model('Account');
    if( $account->isLogin() ) {
        $uid = $account->uid();
        $meta = model('Users')->getMeta($uid);
        $this->view->addData([ 'myMeta' => $meta ]);
    }
});