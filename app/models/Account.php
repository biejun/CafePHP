<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Account extends Model
{
    const LOGIN_KEY = 'login_uid';
    const LOGIN_USER = 'login_name';
    const COOKIE_TOKEN = 'cafe_token';
    
    // 禁止使用的用户名
    protected $disabledName = [
        '@dist',
        '@src',
        'admin',
        'administrator',
        'account',
        'about',
        'add',
        'api',
        'app',
        'answer',
        'assets',
        'auth',
        
        'buy',
        'book',
        'bbs',
        'blog',
        
        'config',
        'captcha',
        'callback',
        'connect',
        'comment',
        'cache',
        'cancel',
        'class',
        'css',
        'create',
        'city',
        'contact',
        
        'delete',
        'detail',
        'download',
        'dist',
        'dest',
        'default',
        
        'edit',
        'email',
        'explore',
        'enterprise',
        
        'fuck',
        'forget',
        'font',
        'find',
        'follow',
        
        'get',
        
        'help',
        'home',
        
        'image',
        'index',
        'install',
        
        'job',
        
        'login',
        'link',
        
        'message',
        'mobile',
        'main',
        'movie',
        'music',
        'mark',
        
        'new',
        'news',
        'notify',
        'notification',
        'note',
        
        'open',
        
        'post',
        'put',
        'photo',
        'page',
        'password',
        'product',
        'platform',
        'price',
        'privacy',
        
        'question',
        
        'register',
        'report',
        'reset',
        'redirect',
        
        'send',
        'shop',
        'sell',
        'signin',
        'signup',
        'static',
        'static-assets',
        'sources',
        'search',
        'sex',
        'share',
        'service',
        'style',
        
        'team',
        'tell',
        'template',
        'tag',
        'topic',
        'terms',
        
        'update',
        'upload',
        'user',
        
        'vip',
        'video',
        'vote',
        'vpn',
        
        'welcome',
        'work',
        'workbench',
        
        '123',
        '1234',
        '12345'
    ];
   
    // 用户是否无效
    public function isInvalidName($name)
    {
        return in_array($this->disabledName, [ $name ]);
    }
    
    public function auth()
    {
        if ( $this->isLogin() ) {
            return true;
        }
        
        $token = $this->getToken();
        
        if (is_null($token)) {
            return false;
        }
        
        if(!$this->recordInfo($token)) {
            return false;
        }
        
        return true;
    }
    
    public function recordInfo($token)
    {
        $tokenResult = model('Users')->checkToken($token);
        
        if(!$tokenResult) {
            return false;
        }
        
        $this->setInfo($tokenResult->uid, $tokenResult->name);
        
        return true;
    }
    
    public function isLogin()
    {
        return !is_null(app('session')->get(self::LOGIN_KEY));
    }
    
    public function setInfo($uid, $name) 
    {
        // 如果令牌是正确的就将当前用户信息存到临时会话中
        app('session')->set(self::LOGIN_KEY, $uid);
        app('session')->set(self::LOGIN_USER, $name);
    }
    
    public function setToken($token = '', $timeout)
    {
        app('cookie')->set(self::COOKIE_TOKEN, $token, $timeout);
        return $this;
    }
    
    public function getToken()
    {
        return app('cookie')->get(self::COOKIE_TOKEN);
    }
    
    public function uid() 
    {
        return app('session')->get(self::LOGIN_KEY);
    }
    
    public function name() 
    {
        return app('session')->get(self::LOGIN_USER);
    }
}