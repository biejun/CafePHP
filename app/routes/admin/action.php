<?php
/* 检查是否为管理员 */
$action->add('auth:permissions', function () {
    $account = model('Account');
    $isLogin = $account->auth();
    
    if (!$isLogin) {
        /* 要是没有登录就重定向到指定页 */
        $this->response->redirect(u('login?ref='.$this->request->getUri()));
    }
    $uid = $account->uid();
    
    if( is_null($uid) || !model('Admin')->is($uid) ) {
        if ($this->request->isAjax()) {
            $this->response->status(401)->json('您没有访问权限!', false);
        } else {
            $this->response->status(401);
            $this->render('common::401');
        }
    }
});

$action->single('route:view',function(){
    $this->view->folder('admin');
});

$action->add('admin:menu', function () {
    $menu = [
        'menu' => [
            [
                'title' => '首页',
                'path' => u()
            ],
            [
                'title' => '用户管理',
                'path' => '',
                'children' => [
                    
                ]
            ],
            [
                'title' => '内容管理',
                'path' => '',
                'children' => [
                    [
                        'title' => '添加用户',
                        'path' => ''
                    ],
                    [
                        'title' => '用户列表',
                        'path' => ''
                    ]
                ]
            ],
            [
                'title' => '系统管理',
                'path' => '',
                'children' => [
                    [
                        'title' => '数据备份',
                        'path' => ''
                    ],
                    [
                        'title' => '系统日志',
                        'path' => ''
                    ]
                ]
            ]
        ]
    ];
    $this->view->addData($menu, 'header');
});