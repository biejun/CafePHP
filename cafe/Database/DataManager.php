<?php namespace Cafe\Database;

use PDO;

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

class DataManager
{
    /* 数据库连接句柄 */
    static $handler;

    private $result = null;
    /* 执行影响行数 */
    public $rowsAffected;

    /* 数据库字符编码集 */
    protected $charset;

    protected $collate;

    public $database = '';

    /* 数据库语句拼接 */
    public $sql = '';

    public function __construct($conf)
    {
		$db = isset($conf['name']) ? $conf['name'] : null;
		$charset = isset($conf['charset']) ? $conf['charset'] : 'utf8';
		
        if(is_null(self::$handler))
        {
			$host = isset($conf['host']) ? $conf['host'] : 'localhost';
			$user = isset($conf['user']) ? $conf['user'] : '';
			$port = isset($conf['port']) ? $conf['port'] : 3306;
			$password = isset($conf['password']) ? $conf['password'] : 'password';
			
			$dsn = "mysql:host=$host;port=$port;charset=$charset";
			try{
				self::$handler = new PDO($dsn, $user, $password,[
					PDO::ATTR_CASE => PDO::CASE_NATURAL,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
					PDO::ATTR_STRINGIFY_FETCHES => false,
					PDO::ATTR_EMULATE_PREPARES => false,
				]);
			}catch(PDOException $e) {
				throw new \Exception($e->getMessage());
			}
        }
		
		$this->setCharset($charset);
		$this->connect($db);
    }

    public function setCharset($charset = 'utf8', $collate = null)
    {
        $this->charset = $charset;
        if (is_null($collate)) {
            if('utf8mb4' === $this->charset){
                $this->collate = 'utf8mb4_general_ci';
            }else{
                $this->collate = 'utf8_general_ci';
            }
        }else{
            $this->collate = $collate;
        }
        return $this;
    }

    # 查询单条数据 返回一个数组
    public function row($array_type = PDO::FETCH_ASSOC)
    {
        return $this->result->fetch($array_type);
    }
    # 查询多条数据
    public function rows($array_type = PDO::FETCH_ASSOC)
    {
        return $this->result->fetchAll($array_type);
    }

    # 查询单个字段的值
    public function one()
    {
        $row = $this->row(PDO::FETCH_NUM);
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
		try{
			$this->create($database);
			$this->exec("use $database;");
		}catch(PDOException $e) {
			throw new \Exception($e->getMessage());
		}
		$this->database = $database;
        return $this;
    }

    # 创建数据库
    public function create($database)
    {
		return $this->exec(
			sprintf(
				"CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET %s COLLATE %s;",
				$database,
				$this->charset,
				$this->collate
			)
		);
    }

    # 删除数据库
    public function drop()
    {
        $this->query(sprintf("DROP DATABASE `%s`;",$this->database));
        if(!$this->result){
            throw new \Exception("删除数据库'{$database}'失败！");
        }
    }

    # 数据库备份
    public function export()
    {
        $sql = "use {$this->database};\r\n\r\n\r\n";
        $this->query("SHOW TABLES FROM `".$this->database."`");		
        if($this->result){
			$tables = array_column($this->result->fetchAll(PDO::FETCH_NUM), 0);
			foreach ($tables as $v) {
				$sql.="DROP TABLE IF EXISTS `$v`;\r\n";
				$this->query("show create table $v");
				$rs=$this->result->fetch(PDO::FETCH_NUM);
				$sql.= $rs[1].";\r\n\r\n";
			}
			
			foreach ($tables as $v) {
				$this->query("select * from $v");
				$rows = $this->result->fetchAll(PDO::FETCH_NUM);
				$columnCount = $this->result->columnCount();
				
				foreach ($rows as $row) {
					$comma="";
					$sql.="insert into `$v` values(";
					for( $i=0; $i < $columnCount; $i++) {
						$sql.=$comma."'".addslashes($row[$i])."'";
						$comma = ",";
					}
					$sql.=");\n";
				}
				$sql.="\n";
			}
        }else{
            throw new \Exception("导出数据库'{$this->database}'失败！");
        }
        return $sql;
    }
    # 取得插入数据的ID
    public function id()
    {
        return self::$handler->lastInsertId();
    }
	
	/* 执行查询语句 */
    public function query($sql=null,$debug=false)
    {
        if(!empty($this->sql)&&is_null($sql)) $sql = $this->sql;
        $this->result = null;
        if($debug) throw new \Exception($sql);
        $this->result = self::$handler->query($sql);
        $this->rowsAffected = $this->result->rowCount();
        return $this;
    }
	
    /* 仅执行SQL语句 */
    public function exec($sql=null,$debug=false)
    {
        if(!empty($this->sql)&&is_null($sql)) $sql = $this->sql;
        if($debug) throw new \Exception($sql);
        return self::$handler->exec($sql);
    }

    public function result()
    {
        return $this->result;
    }

    # 开启事务 数据库引擎为InnoDB时使用此函数
    public function beginTransaction()
    {
        self::$handler->beginTransaction();
    }
	public function commit()
	{
	    self::$handler->commit();
	}
    # 判断执行情况，返回事务状态
    public function rollback()
    {
        if(self::$handler->inTransaction()){
            self::$handler->rollback(); # 执行事务回滚
        }
    }

    /**
     * 创建插入多条数据SQL语句
     *
     * @param  array  $fields  字段 array('category','price','stock');
     * @param  array  $records 多条数据记录 array(array('friuts','199','999'),array('drinks','199','999'));
     * @param  boolean $debug  调试返回值是否正确
     * @return string (`category`,`price`,`stock`) VALUES ('friuts', '199', '999'), ('drinks', '199', '999');
     */
    public function insertRows($fields=array(),$records=array(),$debug=false)
    {
        if(empty($fields)||empty($records)) return false;
        $number_fields = count( $fields );
        $keys = array();
        $sql = '';
        // 遍历所有字段
        foreach ($fields as $field) {
            $keys[] = '`'.$field.'`';
        }
        $keys = "(".implode(',', $keys).")";
        $values = array();
        foreach( $records as $record ){
            // 如果值与字段数相匹配
            if( count($record) == $number_fields )
                $values[] = "('". implode( "', '", array_map('addslashes', array_values($record))) ."')";
        }
        $values = implode( ", ", $values);
        $sql .= $keys .' VALUES '. $values .';';
        if($debug) throw new \Exception($sql);
        return $sql;
    }
    # 预执行查询语句
	public function prepare()
	{
		$args = func_get_args();
		if(isset($args) && count($args) > 0) {
			$query = array_shift($args);
			$this->result = self::$handler->prepare($query);
			$this->result->execute($args);
		}
		return $this;
	}
    # 关闭连接
    public function __destruct()
    {
        if (!self::$handler) return false;
        self::$handler = null;
    }
}