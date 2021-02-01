<?php
/* 路由中间件介绍 */

/* 路由器初始化时挂载的动作 */
$action->add('route:init',function(){
    /* 判断系统是否已安装 */
    if($this->existLock())
    {
        /* 读取站点配置 */
        $options = $this->load('welcome@options')->select('name,value');
        $this->view->options = new stdClass;
        foreach($options as $value) {
            $this->view->options->{$value['name']} = $value['value'];
        }
    }
    /* 设置视图读取文件夹 */
    $this->view->folder('default');
});

/* 路由请求响应前挂载的动作 */
$action->add('route:before',function(){

});

/* 路由请求响应后挂载的动作 */
$action->add('route:after',function(){

});

/* 路由请求响应失败后挂载的动作 */
$action->add('route:failed',function(){

});

/* 检查登录 */
$action->add('check:login',function($redirect = null){

    $allowAccess = false;
    // 验证当前临时会话窗口是否已存在登录记录
    if(is_null($this->session->get('login_uid'))){ // 不存在则检查浏览器中是否存在登录令牌
        $loginToken = $this->cookie->get('user_login_token'); // 取出令牌
        if(!is_null($loginToken)){
            // 检查令牌
            $tokenResult = $this->load('welcome@users')->checkToken($loginToken);
            if($tokenResult){
                $allowAccess = true;
                // 如果令牌是正确的就将当前用户信息存到临时会话中
                $this->session->set('login_uid',$tokenResult['id']);
                $this->session->set('login_name',$tokenResult['name']);
                // 将当前已登录用户信息写进视图模型
                $this->view->account = new stdClass;
                $this->view->account->uid = $tokenResult['id'];
                $this->view->account->name = $tokenResult['name'];
            }
        }
    }else{
        $allowAccess = true;
        $this->view->account = new stdClass;
        $this->view->account->uid = $this->session->get('login_uid');
        $this->view->account->name = $this->session->get('login_name');
    }

    if(!$allowAccess){
        if(is_null($redirect)){
            /* 没有设置指定页就按请求方式返回信息 */
            if($this->request->isAjax()){
                $this->response->json('请先登录!',false);
            }else{
                $this->response->status(403);
                $this->view('403');
            }
        }else{
            /* 要是没有登录就重定向到指定页 */
            $this->response->redirect($redirect);
        }
    }
});
/* 检查安装 */
$action->add('check:install', function() {
    if(!$this->existLock())
    {
        $this->view->folder('install')->assign('token',TOKEN)
            ->assign('checkVersion',version_compare(PHP_VERSION,'5.5.0', '>'));
        $this->view('install');
    }
});