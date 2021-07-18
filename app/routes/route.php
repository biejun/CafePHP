<?php
/* 首页 */
$route->on('check:install')->on('my:meta')->get('/', function ($req, $res) {
    $data = [
        'domain' => $req->getDomain()
    ];
    $this->render('index', $data);
});

$route->on('my:meta')->get('/dashboard', function ($req, $res) {
    $data = [
        'recommTopics' => model('Topics')->recomm(),
        'posts' => model('Posts')->list()
    ];
    $this->view->addData([
        'title' => '工作台'
    ], 'common::layout');
    $this->render('dashboard', $data);
});

/* 登录 */
$route->get('/login', function ($req, $res) {
    $csrf = md5(uniqid(rand(), true));
    app('session')->set('login_csrf', $csrf);
    $data = [
      'token' => TOKEN,
      '__csrf__' => $csrf,
      'title' => '登录',
      'domain' => $req->getDomain()
    ];
    $this->render('login', $data);
});
/* 注册 */
$route->get('/register', function ($req, $res) {
    $csrf = md5(uniqid(rand(), true));
    app('session')->set('register_csrf', $csrf);
    $data = [
      'token' => TOKEN,
      '__csrf__' => $csrf,
      'title' => '注册',
      'domain' => $req->getDomain()
    ];
    $this->render('register', $data);
});
/* 帮助 */
$route->get('/help', function () {
    $this->render('help');
});

$route->get('/captcha/:token/:id', function ($req, $res) {
    $token = $req->param('token');
    $id = $req->param('id');
    if (isset($token) && isset($id)) {
        $res->header('Content-Type', 'image/jpeg')
          ->write(app('captcha')->create($id))
          ->send();
    }
});

$route->on('my:meta')->get('/:name/:cate?/:id?', function ($req, $res) {
    $name = $req->param('name');
    $account = model('Account');
	if(!isset($name) || $account->isInvalidName($name)) {
        $this->render('common::404');
    }
    $users = model('Users');
    $userInfo = $users->getUserInfoByName($name);
    if($userInfo) {
        $uid = $userInfo->uid;
        $userMeta = $users->getMeta($uid);
        $title = $userMeta->nickname ."(@".$name.")";
        
        $cate = $req->param('cate');
        
        if($cate === 'post') {
            $id = $req->param('id');
            
            if( !empty($id) ) {
                $postData = model('Posts')->read($uid, $id);
                $postTitle = empty($postData->post_title) ?: $postData->post_title . ' - ';
                $data = [
                  'post' => $postData,
                  'comments' => model('Comments')->pager($postData->post_id)
                ];
                $this->view->addData([
                    'title' => $postTitle . $title
                ], 'common::layout');
                $this->render('post-detail', $data);
            }
        }else{
            // $follow = $this->load('user@follows');
            // $posts = $this->load('post@posts');
            // $statis = [
            //     'posts' => number_format($posts->count($uid)),
            //     'follows' => number_format($follow->getFollows($uid)),
            //     'follower' => number_format($follow->getFollower($uid))
            // ];
            
            // $this->view->assign('title', $title);
            // $this->view->assign('userMeta', $userMeta);
            // $this->view->assign('userStatis', $statis);
            // $this->view('user');
        }
    }else{
        die('用户不存在');
    }
});