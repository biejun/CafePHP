<?php

namespace Coffee;

class Loader
{
	private static $_namespace = [];

	/**
	 *	自动加载类
	 *
	 * 	@param string
	 */
	public static function autoload($class)
	{
		$prefix = $class;

		while (false !== $pos = strrpos($prefix, '\\')) {

			$prefix = substr($class, 0, $pos + 1);

			$relativeClass = substr($class, $pos + 1);

			$mappedFile = self::loadMappedFile($prefix, $relativeClass);
			if ($mappedFile) {
			    return $mappedFile;
			}

			$prefix = rtrim($prefix, '\\');
		}

		return false;
	}

	/**
	 *	初始化并添加命名空间
	 */
	public static function register($basePath)
	{
		spl_autoload_register('Coffee\\Loader::autoload', true, true);

		$coffeePath = $basePath.'/coffee';

		$appPath = $basePath.'/app';

		self::addNamespace([ 'Coffee' => $coffeePath,'App' => $appPath]);

		static $_helpers = false;

		if(!$_helpers) {
			$_helpers = true;
			include $coffeePath . '/Support/helpers.php';
		}
	}

	public static function addNamespace($namespace, $baseDir = '')
	{
		if (is_array($namespace)) {
			foreach ($namespace as $prefix => $baseDir) {
				self::addPsr4($prefix . '\\', $baseDir, true);
			}
		} else {
			self::addPsr4($namespace . '\\', $baseDir, true);
		}
	}

	protected static function addPsr4($prefix, $baseDir, $prepend = false)
	{
		$prefix = trim($prefix, '\\') . '\\';

		$baseDir = $baseDir . '/';

		if (isset(self::$_namespace[$prefix]) === false) {
			self::$_namespace[$prefix] = array();
		}

		if ($prepend) {
			array_unshift(self::$_namespace[$prefix], $baseDir);
		} else {
			array_push(self::$_namespace[$prefix], $baseDir);
		}
	}

	protected static function loadMappedFile($prefix, $relativeClass)
	{

		if (isset(self::$_namespace[$prefix]) === false) {
			return false;
		}

		foreach (self::$_namespace[$prefix] as $baseDir) {

			$file = $baseDir
				. str_replace('\\', '/', $relativeClass)
				. '.php';

			if (self::requireFile($file)) {
				return $file;
			}else{
				throw new \Exception("没有找到".$file."文件", 1);
			}
		}

		return false;
	}

	public static function requireFile($file)
	{
		if (file_exists($file)) {
			require $file;
			return true;
		}
		return false;
	}
}