<?php namespace Coffee\Database;
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

class DataManager
{
    /* 数据库连接句柄 */
    static $handler;

    private $result = null;
    /* 执行影响行数 */
    public $rowsAffected;
    /* 表前缀 */
    protected $prefix = '';

    /* 数据库字符编码集 */
    protected $charset;

    protected $collate;

    /* 数据库语句拼接 */
    public $sql = '';

    public function __construct()
    {   
        $db = $this->configs();
        if(is_null(self::$handler)) {
            self::$handler = new \mysqli($db['host'], $db['user'], $db['password'], $db['port']);
            if(self::$handler->connect_errno){
                throw new \Exception('Connect Error (' .
                    self::$handler->connect_errno . ') '.
                    self::$handler->connect_error);
            }
            $this->setPrefix($db['prefix']);
            $this->setCharset( (isset($db['charset']) ? $db['charset'] : 'utf8'),
                (isset($db['collate']) ? $db['collate'] : null ));
        }
        $this->connect($db['name']);
    }

    public function configs()
    {
        static $_conf;
        if(is_null($_conf)) {
            $file = CONFIG . '/config.db.php';
            if( !file_exists($file) ) throw new \Exception("数据库配置文件不存在！");
            $_conf = include($file);
        }
        return $_conf;
    }

    public function setCharset($charset = 'utf8', $collate = null)
    {
        $this->charset = $charset;
        if (is_null($collate)) {
            if('utf8mb4' === $this->charset){
                $this->collate = 'utf8mb4_unicode_ci';
            }else{
                $this->collate = 'utf8_general_ci';
            }
        }else{
            $this->collate = $collate;
        }
        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    # 查询单条数据 返回一个数组
    public function row($array_type = MYSQLI_ASSOC)
    {
        $row = [];
        if ($this->result){
            $row = $this->result->fetch_array($array_type);
        }
        return $row;
    }
    # 查询多条数据
    public function rows()
    {
        $rows = [];
        if($this->result){
            while ($row = $this->result->fetch_assoc()){
                $rows[] = $row;
            }
            $this->flush($this->result);
        }
        return $rows;
    }

    # 查询单个字段的值
    public function one()
    {
        $row = $this->row(MYSQLI_NUM);
        return array_shift($row);
    }

    /**
     * 连接指定数据库
     *
     * @param  string  $database 数据库名
     * @param  boolean $create  数据库不存在是否新建
     * @return $this
     */
    public function connect($database = null)
    {
        if($database === null) return false;
        if(!self::$handler->select_db($database)){
            // 自动创建数据库
            $this->create($database);
            if(!self::$handler->select_db($database)){
                throw new \Exception("Can't select MySQL database(".$database.")!");
            }
        }
        self::$handler->set_charset($this->charset);
        return $this;
    }

    # 创建数据库
    public function create($database)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET %s COLLATE %s;";
        $this->query(sprintf($sql,$database,$this->charset,$this->collate));
        if(!$this->result){
            throw new \Exception("创建数据库'{$database}'失败！");
        }
    }

    # 删除数据库
    public function drop($database)
    {
        $this->query(sprintf("DROP DATABASE `%s`;",$database));
        if(!$this->result){
            throw new \Exception("删除数据库'{$database}'失败！");
        }
    }

    # 数据库备份
    public function export($database)
    {
        $sql='';
        $this->query("SHOW TABLES FROM `{$database}`");
        if($this->result){
            $array = array();
            while ($row = $this->result->fetch_row()){
                $array[] = current($row);
            }
            if(!empty($array)){
                foreach ($array as $v){
                    $sql.="DROP TABLE IF EXISTS `$v`;\n";
                    $this->query("show create table $v");
                    $rs=$this->result->fetch_row();
                    $sql.=$rs[1].";\n\n";
                }
                foreach ($array as $v){
                    $this->query("select * from $v");
                    $field = $this->result->field_count;
                    while ($rs = $this->result->fetch_array()) {
                        $comma="";
                        $sql.="insert into `$v` values(";
                        for($i=0;$i<$field;$i++){
                            $sql.=$comma."'".self::$handler->escape_string($rs[$i])."'";
                            $comma = ",";
                        }
                        $sql.=");\n";
                    }
                    $sql.="\n";
                }
            }
        }else{
            throw new \Exception("导出数据库'{$database}'失败！");
        }
        return $sql;
    }

    # 资源回收
    private function flush($result)
    {
        if( $result instanceof mysqli_result ){
            $result->free();
        }
    }
    # 取得插入数据的ID
    public function id()
    {
        return self::$handler->insert_id;
    }

    /* 执行SQL语句 */
    public function query($sql=null,$debug=false)
    {
        if(!empty($this->sql)&&is_null($sql)) $sql = $this->sql;
        $this->result = null;
        if($debug) throw new \Exception($sql);
        $this->result = self::$handler->query($sql);
        if(self::$handler->error){
            throw new \Exception(self::$handler->error);
        }
        $this->rowsAffected = self::$handler->affected_rows;
        return $this;
    }

    public function result()
    {
        return $this->result;
    }

    # 取得当前数据库版本
    public function version()
    {
        return preg_replace('/[^0-9.].*/', '', self::$handler->server_info);
    }

    # 开启事务 数据库引擎为InnoDB时使用此函数
    public function autocommit()
    {
        self::$handler->autocommit(false);
    }
    # 判断执行情况，返回事务状态
    public function checkcommit()
    {
        if(self::$handler->errno){
            self::$handler->rollback(); # 执行事务回滚
        }else{
            self::$handler->commit();   # 提交事务
        }
    }
    # 防止SQL注入
    public function escape($data)
    {
        if ( is_array( $data ) ) {
            foreach ( $data as $k => $v ) {
                if ( is_array( $v ) ){
                    $data[$k] = $this->escape($v);
                }else{
                    $data[$k] = $this->_real_escape($v);
                }
            }
        } else {
            $data = $this->_real_escape($data);
        }
        return $data;
    }
    /**
     * 预执行查询语句.
     *
     * 可以在查询格式字符串中使用以下指令:
     *   %d (integer) 数字型
     *   %f (float) 浮点型
     *   %s (string) 字符串型
     *   %% (literal percentage sign - no argument needed)
     */
    public function prepare()
    {
        $args = func_get_args ();
        $query = array_shift($args);

        $query = str_replace('~prefix~', $this->prefix, $query);
        $query = str_replace('~charset~', $this->charset, $query);
        $query = str_replace('~collate~', $this->collate, $query);
        if(strpos($query, '%')){
            if(count($args)>0){
                $query = str_replace( "'%s'", '%s', $query );
                $query = str_replace( '"%s"', '%s', $query );
                $query = preg_replace( '|(?<!%)%f|' , '%F', $query );
                $query = preg_replace( '|(?<!%)%s|', "'%s'", $query );
                array_walk( $args, array( $this, 'escape_by_ref' ) );
                $this->sql = @vsprintf( $query, $args );
            }
        }
        $this->sql = $query;
        return $this;
    }
    private function _real_escape($string)
    {
        if(self::$handler){
            return self::$handler->real_escape_string($string);
        }
        return addslashes($string);
    }
    /**
     * 通过引用插入数据库中的内容，以确保安全性
     *
     * @uses $this->prepare()
     * @param string $string to escape
     */
    public function escape_by_ref( &$string ) {
        if ( ! is_float( $string ) )
            $string = $this->_real_escape( $string );
    }
    # 关闭连接
    public function __destruct()
    {
        if (!self::$handler) return false;
        self::$handler->close();
        self::$handler = null;
    }
}