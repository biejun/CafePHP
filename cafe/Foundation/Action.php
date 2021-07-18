<?php namespace Cafe\Foundation;

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

class Action
{
    private static $_actions = [];
    /**
     *  增加一个行为钩子函数
     *
     *  @param string $action 钩子函数名
     *  @param function $function 当前需要挂载的函数
     */
    public function add($action, $function)
    {
        $guid = $this->_toGuidString($function);
        if (!isset(static::$_actions[$action][$guid])) {
            static::$_actions[$action][$guid] = $function;
        }
    }
    
    /**
     *  单例模式，重复执行会覆盖此钩子行为
     *
     *  @param string $action 钩子函数名
     *  @param function $function 当前需要挂载的函数
     */
    public function single($action, $function)
    {
        if (!isset(static::$_actions[$action]['single'])) {
            static::$_actions[$action]['single'] = $function;
        }
    }

    /**
     *  执行某个钩子函数
     *
     *  @param string $action 一个已定义的动作钩子，当同名钩子存在多个时将会队列执行
     */
    public function on($action)
    {
        $actions = static::$_actions;
        $args = array_slice(func_get_args(), 1);
        if (isset($actions[$action])) {
            $middlewares = $actions[$action];
            while($function = current($middlewares)) {
                if (!is_null($function)) {
                    call_user_func_array($function, $args);
                }
                next($middlewares);
            }
        }
    }
    /**
     *  执行某个动作钩子的函数
     *
     *  @param string $action 钩子函数名
     */
    public function once($action)
    {
        $actions = static::$_actions;
        $args = array_slice(func_get_args(), 1);
        if (isset($actions[$action]['single'])) {
            $function = $actions[$action]['single'];
            if (!is_null($function)) {
                call_user_func_array($function, $args);
            }
        }
    }

    private function _toGuidString($mix)
    {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }
}
