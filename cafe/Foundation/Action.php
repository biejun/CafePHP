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

class Action
{

    private static $_actions = [];
    /**
     *  挂载一个函数到特定的动作中
     *
     *  @param string $action 一个已定义的动作钩子，当钩子函数执行时，$function同时执行
     *  @param function $function 当前需要挂载的函数
     */
    public function add( $action,$function ){
        $guid = $this->_toGuidString($function);
        if(!isset(static::$_actions[$action][$guid])){
            static::$_actions[$action][$guid] = $function;
        }
    }

    /**
     *  执行某个动作钩子的函数
     *
     *  @param string $action 一个已定义的动作钩子，当钩子函数执行时，$function同时执行
     */
    public function on( $action ){
        $actions = static::$_actions;
        $args = array_slice(func_get_args(), 1);
        if (isset($actions[$action])) {
            foreach ($actions[$action] as $function) 
			{
				if(!is_null($function)) call_user_func_array($function,$args);
			}
        }

    }

    /**
     *  执行某个特定动作钩子的函数
     *
     *  @param string $action 一个已定义的动作钩子
     *  @return mixed 有返回值，返回所有挂载在此类钩子上return的值
     */
    public function apply( $action,$value )
    {

        $actions = static::$_actions;
        $args = func_get_args();
        if (isset( $actions[$action] )) 
		{
			foreach ( $actions[$action] as $function )
			{
				if(!is_null( $function ))
				{
				    $args[1] = $value;
				    $value = call_user_func_array( $function,array_slice( $args,1 ) );
				}
			}
		}
        return $value;
    }

    private function _toGuidString( $mix )
    {
        if (is_object( $mix )) {
            return spl_object_hash( $mix );
        } elseif (is_resource( $mix )) {
            $mix = get_resource_type( $mix ) . strval( $mix );
        } else {
            $mix = serialize( $mix );
        }
        return md5( $mix );
    }

    /**
     *  导出所有的动作
     *
     *  @return array
     */
    public function export()
    {
        return static::$_actions;
    }

}