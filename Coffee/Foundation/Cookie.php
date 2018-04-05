<?php namespace Coffee\Foundation;
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link     https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

class Cookie
{
    /* cookie 前缀 */
    private $prefix = '';

    /* cookie 保存路径 */
    private $path = '/';

    /* cookie 有效域名 */
    private $domain = '';

    /* cookie启用安全传输 */
    private $secure = false;

    /* 设置httponly */
    private $httponly = '';

    public function __construct()
    {
        $this->prefix = '';

        $this->path = '/';

        $httponly = '';

        if(!empty($httponly))
        {
            ini_set('session.cookie_httponly', 1);
        }
    }

    /**
     * 设置前缀
     *
     * @param string $prefix 自定义前缀
     */
    public function setPrefix($prefix = '')
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * 获取指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param string $default 默认的参数
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $key = $this->prefix . $key;

        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    /**
     * 设置指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param mixed $value 设置的值
     * @param integer $expire 过期时间,默认为0,表示随会话时间结束
     * @return void
     */
    public function set($key, $value, $expire = 0)
    {
        $key = $this->prefix . $key;

        setrawcookie($key, rawurlencode($value), $expire, $this->path, $this->domain, $this->secure, $this->httponly);

        $_COOKIE[$key] = $value;
    }

    /**
     * 设置永久的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param mixed $value 设置的值
     * @return void
     */
    public function forever($key, $value)
    {
        $this->set($key, $value, 315360000);
    }

    /**
     * 删除指定COOKIE
     *
     * @access public
     * @param string $key 指定的参数
     * @return void
     */
    public function delete($key)
    {
        $key = $this->prefix . $key;

        if (!isset($_COOKIE[$key])) return;

        setcookie($key, '', $_SERVER['REQUEST_TIME'] - 3600, $this->path, $this->domain, $this->secure, $this->httponly);

        unset($_COOKIE[$key]);
    }
}