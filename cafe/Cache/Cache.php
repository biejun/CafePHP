<?php namespace Cafe\Cache;

/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @version  1.0.0
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

/**
 * 数据缓存类
 *
 * 这是一个基于文件系统的缓存基本类，它不依赖Memcache扩展，
 * 当然你可以通过一些配置让它支持Memcache、Redius
 */
class Cache
{
    /**
     * 用于缓存的目录
     */
    public $dir = '';

    public function __construct($dir = STORAGE.'/datas')
    {
        $this->dir = $dir;

        if (! file_exists($this->dir)) {
            if (! is_writeable(dirname($this->dir))) {
                throw new \Exception("你得先创建{$this->dir}目录才能继续工作！");
            }
            mkdir($this->dir);
            chmod($this->dir, 0777);
        }
    }

    /**
     * 通过一个配置变量初始化缓存对象
     * 支持xcache 与 redius 缓存扩展
     */
    public static function init($conf = array())
    {
        $server = isset($conf['server']) ? $conf['server'] : false; // 服务器地址
        $dir = isset($conf['folder']) ? $conf['folder'] : STORAGE.'/datas'; // 缓存目录
        $extension = isset($conf['extension']) ? $conf['extension'] : 'memcache'; // 缓存扩展类型

        if ('xcache' === $extension && extension_loaded('xcache')) {
            return new \Cafe\Cache\MemcacheXCache();
        } elseif ($server) {
            if ($extension === 'redis' && extension_loaded('redis')) {
                $cache = new \Cafe\Cache\MemcacheRedis();
            } elseif (extension_loaded('memcache')) {
                $cache = new \Memcache();
            } else {
                return new Cache($dir);
            }
            // 连接到缓存服务器
            foreach ($server as $s) {
                list($serv, $port) = explode(':', $s);
                if (strpos($port, ',') !== false) {
                    list($port, $password) = explode(',', $port);
                    $cache->addServer($serv, $port, $password);
                } else {
                    $cache->addServer($serv, $port);
                }
            }
            return $cache;
        }
        // 默认返回文件缓存基类
        return new Cache($dir);
    }

    /**
     * 设置文件超时时间
     */
    private function _set_timeout($key, $timeout)
    {
        if (file_put_contents($this->dir . '/.' . md5($key), $timeout)) {
            chmod($this->dir . '/.' . md5($key), 0666);
            return true;
        }
        return false;
    }

    /**
     * 检查文件是否已过期，如果已超时就删除
     */
    private function _has_timed_out($key)
    {
        $timeout_file = $this->dir . '/.' . md5($key);
        if (! file_exists($timeout_file)) {
            return false;
        }
        $timeout = file_get_contents($timeout_file);
        $mtime = filemtime($timeout_file);
        if ($mtime < time() - $timeout) {
            unlink($timeout_file);
            return true;
        }
        return false;
    }

    /**
     * 读取缓存文件
     */
    public function get($key)
    {
        if (file_exists($this->dir . '/' . md5($key))) {
            if ($this->_has_timed_out($key)) {
                return false;
            }
            $val = file_get_contents($this->dir . '/' . md5($key));
            if (preg_match('/^(a|O):[0-9]+:/', $val)) {
                return unserialize($val);
            }
            return $val;
        }
        return false;
    }

    /**
     * 添加缓存文件，如果文件已存在则不添加
     *
     * Emulates `Memcache::add`.
     */
    public function add($key, $val, $flags = 0, $timeout = false)
    {
        if (is_array($val) || is_object($val)) {
            $val = serialize($val);
        }
        if (file_exists($this->dir . '/' . md5($key))) {
            return false;
        }
        if (! file_put_contents($this->dir . '/' . md5($key), $val)) {
            return false;
        }
        chmod($this->dir . '/' . md5($key), 0666);
        if ($timeout) {
            $this->_set_timeout($key, $timeout);
        }
        return true;
    }

    /**
     * 修改缓存文件，如果文件已存在
     *
     * Emulates `Memcache::set`.
     */
    public function set($key, $val, $flags = 0, $timeout = false)
    {
        if (is_array($val) || is_object($val)) {
            $val = serialize($val);
        }
        if (! file_put_contents($this->dir . '/' . md5($key), $val)) {
            return false;
        }
        chmod($this->dir . '/' . md5($key), 0666);
        if ($timeout) {
            $this->_set_timeout($key, $timeout);
        }
        return true;
    }

    /**
     * 效仿Memcache的replace接口
     *
     * Emulates `Memcache::replace`.
     */
    public function replace($key, $val, $flags = 0, $timeout = false)
    {
        if (is_array($val) || is_object($val)) {
            $val = serialize($val);
        }
        if (! file_put_contents($this->dir . '/' . md5($key), $val)) {
            return false;
        }
        chmod($this->dir . '/' . md5($key), 0666);
        if ($timeout) {
            $this->_set_timeout($key, $timeout);
        }
        return true;
    }

    /**
     * Emulates `Memcache::increment`.
     */
    public function increment($key, $value = 1)
    {
        if (file_exists($this->dir . '/' . md5($key))) {
            $val = file_get_contents($this->dir . '/' . md5($key));
        } else {
            $val = 0;
        }
        $val += $value;
        if (! file_put_contents($this->dir . '/' . md5($key), $val)) {
            return false;
        }
        chmod($this->dir . '/' . md5($key), 0666);
        return $val;
    }

    /**
     * Emulates `Memcache::decrement`.
     */
    public function decrement($key, $value = 1)
    {
        if (file_exists($this->dir . '/' . md5($key))) {
            $val = file_get_contents($this->dir . '/' . md5($key));
        } else {
            $val = 0;
        }
        $val -= $value;
        if (! file_put_contents($this->dir . '/' . md5($key), $val)) {
            return false;
        }
        chmod($this->dir . '/' . md5($key), 0666);
        return $val;
    }

    /**
     * 清空缓存目录
     */
    public function flush()
    {
        $files = glob($this->dir . '/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if (preg_match('/\/\.+$/', $file)) {
                continue;
            }
            unlink($file);
        }
        return true;
    }

    /**
     * 删除某个缓存
     */
    public function delete($key)
    {
        $file = $this->dir . '/' . md5($key);
        if (file_exists($file)) {
            return unlink($this->dir . '/' . md5($key));
        }
        return true;
    }
}
