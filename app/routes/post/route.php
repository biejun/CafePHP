<?php
$route->group('/post', function ($route) {
        
    $route->on('my:meta')->get('/create', function ($req, $res) {
        $csrf = md5(uniqid(rand(), true));
        $type = $req->get('type');
        
        app('session')->set('post_csrf', $csrf);
        
        $data = [
            'type' => $type,
            'csrf' => $csrf
        ];
        $this->view->addData([
            'title' => '新建'
        ], 'common::layout');
        $this->render('write', $data);
    });
    
    $route->on('my:meta')->get('/edit', function ($req, $res) {
        $csrf = md5(uniqid(rand(), true));
        $type = $req->get('type');
        $post_id = $req->get('id');
        
        if(!$post_id) {
            die('ddd');
        }
        
        $post = model('Posts')->readByID($post_id);
        $post['post_content'] = $post['post_content'];
        
        if($post) {
            app('session')->set('post_csrf', $csrf);
            
            $data = [
                'type' => $type,
                'csrf' => $csrf,
                'post' => $post,
                'editPath' => $req->getUri()
            ];
            $this->view->addData([
                'title' => '编辑'
            ], 'common::layout');
            $this->render('write', $data);
        }
    });
    
    $route->get('/topic', function($req, $res) {
        $query = $req->get('q');
        $data = [
           [
               'name' => $query,
               'value' => $query,
               'text' => $query,
           ]
        ];

        $res->json($data, true);
    });
	
    $route->post('/add', function ($req, $res) {        
        $params = [];
        $params['post_title']   = $req->post('post_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $params['post_content'] = $req->post('post_content', FILTER_UNSAFE_RAW);
        $params['post_type']    = $req->post('post_type', FILTER_VALIDATE_INT, 0);
        $params['post_lock']    = $req->post('post_lock', FILTER_VALIDATE_INT, 0);
        $params['post_privacy'] = $req->post('post_privacy', FILTER_VALIDATE_INT, 0);
        $params['post_pass']    = $req->post('post_pass', FILTER_UNSAFE_RAW);
        $params['post_time']    = date('Y-m-d H:i:s');
        $params['uid']          = model('Account')->uid();
        
		if(!$params['post_content'] || empty($params['post_content'])) {
			$res->json('内容不能为空！', false);
		}
        
        $posts = model('Posts');
        
        $params['post_content'] = $posts->parseContent($params['post_content']);
        
        if(empty($params['post_content'])) {
            $res->json('提交的内容存在限制字符！', false);
        }
        
        if($params['post_privacy'] == 3) {
            
        }
        
        // 写入数据，并拿到最新的ID
		$postID = $posts->add($params);
        
        $atIds = $req->post('post_at_ids');
        // 提醒圈中的用户
        if(!empty($atIds)) {
            $uids = explode(',', $atIds);
            $notify = model('Notifications');
            foreach($uids as $atUid){
                if($params['uid'] != $atUid) {
                    $notify->addPostAt($params['uid'], $atUid, $postID);
                }
            }
        }
        $topics = $req->post('post_topics');
        // 写入话题
        if(!empty($topics)) {
            $topicNames = explode(',', $topics);
            $topic = model('Topics');
            foreach($topicNames as $topicName){
                $topic->addPostTopic($topicName, $postID);
            }
        }
        
        $images = $req->post('post_images');
        $posts->imageMove($images, $postID, $params['post_content']);
        
        if($postID > 0) {
            $res->json('发布成功！', true);
        }else{
            $res->json('发布失败！', true);
        }
    });
	
	$route->post('/update', function ($req, $res) {
        $params = [];
        $params['post_id']      = $req->post('post_id', FILTER_VALIDATE_INT, 0);
        $params['post_title']   = $req->post('post_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $params['post_content'] = $req->post('post_content', FILTER_UNSAFE_RAW);
        $params['post_type']    = $req->post('post_type', FILTER_VALIDATE_INT, 0);
        $params['post_lock']    = $req->post('post_lock', FILTER_VALIDATE_INT, 0);
        $params['post_privacy'] = $req->post('post_privacy', FILTER_VALIDATE_INT, 0);
        $params['post_pass']    = $req->post('post_pass', FILTER_UNSAFE_RAW);
        $params['post_update']  = date('Y-m-d H:i:s');
        $params['uid']          = model('Account')->uid();
        
		if(!$params['post_id'] || $params['post_id'] === 0) {
			$res->json('缺少ID参数！', false);
		}
        
        if(!$params['post_content'] || empty($params['post_content'])) {
        	$res->json('内容不能为空！', false);
        }
        
        // 获取@用户ID
        $atIds = $req->post('post_at_ids');
        $atOldIds = $req->post('post_at_old_ids');
        // 比对内容中@用户的变化
        if(!empty($atOldIds) || !empty($atIds)) {
            $atOldIds = explode(',', $atOldIds);
            $atIds = explode(',', $atIds);
            $delIds = $atOldIds;
            $newIds = [];
            foreach($atIds as $uid){
                if($params['uid'] != $uid) {
                    $index = array_search($uid, $delIds);
                    if($index > -1) {
                        array_splice($delIds, $index, 1);
                    }else{
                        array_push($newIds, $uid);
                    }
                }
            }
            
            $notify = model('Notifications');
            // 删掉被撤销的提醒
            if(count($delIds) > 0) {
                $notify->delPostAt($params['uid'], implode(",", $delIds), $params['post_id']);
            }
            // 增加新的提醒
            if(count($newIds) > 0) {
                foreach($newIds as $uid) {
                    $notify->addPostAt($params['uid'], $uid, $params['post_id']);
                }
            }
        }
        
        $topicOldIds = $req->post('post_topic_old_ids');
        $topicIds = $req->post('post_topic_ids');
        
        // 获取内容关联的话题ID
        if(!empty($topicOldIds) || !empty($topicIds)) {
            
        }
        $posts = model('Posts');
        $params['post_content'] = $posts->parseContent($params['post_content']);
                
		$status = $posts->update($params);
		echo (int) $status;
	});
    
    $route->post('/delete', function () {
    });
    
    $route->group('/digg', function ($route) {
        $route->post('/add', function () {
        });
        
        $route->post('/delete', function () {
        });
    });
    
    $route->group('/comment', function ($route) {
        $route->post('/add', function ($req, $res) {
            // 检查参数是否存在
            $params = [];
            $params['post_id']         = $req->post('post_id', FILTER_VALIDATE_INT, 0);
            $params['comment_content'] = $req->post('comment_content', FILTER_UNSAFE_RAW);
            $params['comment_id']      = $req->post('comment_id', FILTER_VALIDATE_INT, 0);
            
            if(!$params['comment_content'] || empty($params['comment_content'])) {
            	$res->json('评论内容不能为空！', false);
            }
            
            $comment = model('Comments');
            
            $params['comment_content'] = $comment->parseContent($params['comment_content']);
            $params['uid']             = model('Account')->uid();
			$newId = $comment->add($params);
			echo $newId;
        });
        
        $route->post('/delete', function () {
            
        });
        
        $route->group('/reply', function($route) {
            
            $route->post('/add', function ($req, $res) {
                $params = [];
                $params['reply_content'] = $req->post('reply_content', FILTER_UNSAFE_RAW);
                $params['comment_id']    = $req->post('comment_id', FILTER_VALIDATE_INT, 0);
                $params['to_reply_id']   = $req->post('to_reply_id', FILTER_VALIDATE_INT, 0);
                $params['to_reply_uid']  = $req->post('to_reply_uid', FILTER_VALIDATE_INT, 0);
                
                $replies = model('Replies');
                
                $params['reply_content'] = $replies->parseContent($params['reply_content']);
                if(empty($params['reply_content'])) {
                    $res->json('内容不合法！', false);
                }
                $params['uid']           = model('Account')->uid();
            	$newId = $replies->add($params);
            	echo $newId;
            });
            
            $route->post('/delete', function () {
                
            });
        });
    });
    
    // 图片上传（存储在本地磁盘）
    $route->post('/upload/image', function ($res, $req) {
        
        $file = $_FILES["upload_file"];
        
        if($file['name'] === 'blob') {
            $file['name'] = $res->post('original_filename');
        }
        
        $upload = new Cafe\Image\Upload();
        
        $error = $upload->check($file);
        
        if(!empty($error)) {
            $req->status(400)->json($error, false);
        }
        $filepath = $upload->save($file, '/post/_tmp/'.date('Ymd').'/');
        if(!empty($filepath)) {
            $req->json(['filePath' => $filepath], true);
        }
    });
});
