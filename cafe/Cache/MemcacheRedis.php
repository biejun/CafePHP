<?php namespace Coffee\Cache;

class MemcacheRedis {
	/**
	 * Redis connection object.
	 */
	public static $redis;

	public function __construct ($redis = false) {
		if ($redis !== false) {
			self::$redis = $redis;
		} else {
			self::$redis = new Redis ();
		}
	}

	/**
	 * 给Redius添加一个类似Memcache的addServer接口
	 */
	public function addServer ($server, $port = 6379, $password = false) {
		$res = self::$redis->connect ($server, $port);
		if ($res) {
			if ($password !== false) {
				self::$redis->auth ($password);
			}
			self::$redis->setOption (Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
		return $res;
	}

	public function get ($key) {
		return self::$redis->get ($key);
	}

	public function add ($key, $value, $flag = 0, $expire = false) {
		if (self::$redis->exists ($key)) {
			return false;
		}
		return $this->set ($key, $value, $flag, $expire);
	}

	public function set ($key, $value, $flag = 0, $expire = false) {
		if ($expire) {
			return self::$redis->setex ($key, $expire, $value);
		}
		return self::$redis->set ($key, $value);
	}

	public function replace ($key, $value, $flag = 0, $expire = false) {
		if (! self::$redis->exists ($key)) {
			return false;
		}
		return $this->set ($key, $value, $flag, $expire);
	}

	/**
	 * 删除缓存
	 */
	public function delete ($key) {
		return self::$redis->delete ($key);
	}

	public function increment ($key, $value = 1) {
		if ($value === 1) {
			return self::$redis->incr ($key);
		}
		return self::$redis->incrBy ($key, $value);
	}

	public function decrement ($key, $value = 1) {
		if ($value === 1) {
			return self::$redis->decr ($key);
		}
		return self::$redis->decrBy ($key, $value);
	}

	/**
	 * 清空缓存
	 */
	public function flush () {
		return self::$redis->flushDB ();
	}
}
