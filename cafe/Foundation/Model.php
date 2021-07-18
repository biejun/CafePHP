<?php namespace Cafe\Foundation;

class Model
{
    
    protected static $models = [];
    
    /**
     *  执行当前类中的方法
     *
     *  @param string $func 回调函数
     *  @param array $args 回调函数参数
     *  @return mixed
     *
    **/
    public function run($func, $args)
    {
        $reflection = new \ReflectionClass($this);
        $parentClass = $reflection->getParentClass();
    
        if ($parentClass) {
            $parentMethods = $parentClass->getMethods();
            // 过滤父类方法
            while ($it = current($parentMethods)) {
                if ($func === $it->getName()) {
                    return false;
                } else {
                    next($parentMethods);
                }
            }
            if ($reflection->hasMethod($func)) {
                $method = $reflection->getMethod($func);
                if ($method->isPublic()) {
                    $params = [];
                    foreach ($method->getParameters() as $arg) {
                        if ($args[$arg->name]) {
                            $params[$arg->name] = $args[$arg->name];
                        } else {
                            $params[$arg->name] = null;
                        }
                    }
                    return $method->invokeArgs($this, $params);
                }
            }
        }
        return false;
    }
    
    /**
     * 复制当前组件到一个新变量中
     *
     * @param string $variable 变量名
     * @return void
     */
    public function to(&$variable)
    {
        return $variable = $this;
    }
    
    public static function load($name)
    {
        $name = '\\App\\Models\\'.ucfirst($name);
        if(self::$models[$name]) {
            return self::$models[$name];
        }
        self::$models[$name] = new $name();
        return self::$models[$name];
    }
}