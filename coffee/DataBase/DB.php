<?php
/**
 * AnyPHP Coffee
 *
 * An agile development core based on PHP.
 *
 * @version  0.0.6
 * @link 	 https://github.com/biejun/anyphp
 * @copyright Copyright (c) 2017-2018 Jun Bie
 * @license This content is released under the MIT License.
 */

/**
 * 数据库操作类
 *
 * 使用方法:
 * $this->db->from('student')->select('name,age')->where('`sex`=%d and `age`>=%d',1,18)->rows();
 *
 * @uses Coffee\Fondation\Component
 * @package Coffee\DataBase\DB
 * @since 0.0.5 包含了增删改查备份等功能
 */

namespace Coffee\DataBase;

class DB
{

	/* 数据库连接句柄 */
	public $handler = null;

	/* 执行影响行数 */
	public $rowsAffected;

	/* 数据库 */
	protected $database = null;

	/* 表前缀 */
	protected $prefix = '';

	/* 数据库表名 */
	protected $table = '';

	/* 数据库表别名 */
	protected $tableAlias = '';

	/* 数据库语句拼接 */
	protected $sql = '';

	/* 数据库字符编码集 */
	protected $charset;

	protected $collate;

	public function __construct($db)
	{
		if(count($db) > 0){

			$this->prefix = $db['prefix'];
			$this->database = $db['name'];
			$this->handler = new \mysqli($db['host'], $db['user'], $db['password'], $db['port']);
			if($this->handler->connect_errno){
				throw new \Exception('Connect Error (' . $this->handler->connect_errno . ') '. $this->handler->connect_error);
			}
			$this->setCharset();
		}else{
			throw new \Exception('数据库初始化失败，请检查数据库配置文件');
		}
	}
	/* 设置数据库字符集编码 */
	public function setCharset()
	{
		if ($charset = G('database','charset')) {
			$this->charset = $charset;
			if ($collate = G('database','collate')) {
				$this->collate = $collate;
			}else{
				if('utf8mb4' === $this->charset){
					$this->collate = 'utf8mb4_unicode_ci';
				}else{
					$this->collate = 'utf8_general_ci';
				}
			}
		}
	}

	/**
	 * 连接指定数据库
	 *
	 * @param  string  $database 数据库
	 * @param  boolean $created  数据库不存在是否新建
	 * @return $this
	 */
	public function connect($database = null,$created = false)
	{
		$database = is_null($database) ? $this->database : $database;
		$exists = $this->handler->select_db($database);
		if(!$exists){
			// 自动创建数据库
			if($created){
				$this->createDatabase($database);
				$this->handler->select_db($database);
			}else{
				throw new \Exception("Can't select MySQL database(".$database.")!");
			}
		}
		$this->handler->set_charset($this->charset);

		return $this;
	}

	/* 设置数据库表 */
	public function from($table, $alias = null)
	{
		$this->table = $this->prefix . $table;
		if(!is_null($alias)) $this->alias = $alias;
		return $this;
	}

	/* 组装SQL SELECT */
	public function select($select = null)
	{
		if(empty($this->table)) throw new \Exception('缺少数据库表名 $this->db->from()');

		$select = (is_null($select)) ? '*' : $select;

		$this->sql = "SELECT {$select} FROM `{$this->table}`";

		if(!empty($this->tableAlias)) $this->sql .= " AS {$this->tableAlias}";

		return $this;
	}

	/* 组装SQL WHERE */
	public function where()
	{
		$args = func_get_args ();

		$condition = array_shift($args);

		$this->sql .= ' WHERE '.$this->prepare($condition, $args);

		return $this;
	}

	/* 组装SQL ORDER BY */
	public function order($orderBy)
	{
		$this->sql .= ' ORDER BY {$orderBy}';

		return $this;
	}

	/**
	* 组装SQL JOIN
	* @param string|array $join 数据库表名
	* @param string       $type 连接类型（不需要加join）
	* @param string  $condition 条件（不需要加on）
	* @return $this
	*/
	public function join()
	{
		$args = func_get_args ();

		$join = array_shift($args);

		if(is_array($join)){

			list($table, $alias) = each($join);

			$table = "`{$this->prefix}{$table}` AS {$alias}";
		}else{
			$table = "`{$this->prefix}{$table}`";
		}

		if(isset($args[0]) && in_array(strtoupper($args[0]),['INNER','LEFT','RIGHT'])){
			$type = strtoupper(array_shift($args));
		}else{
			throw new \Exception("缺少连接符号");
		}
		$this->sql .= " {$type} JOIN {$table}";
		if(count($args)>0){
			$condition = array_shift($args);
			$this->sql .= " ON ".$this->prepare($condition,$args);
		}
		return $this;
	}

	/* 执行SQL语句 */
	public function query($sql)
	{
		$exec = $this->handler->query($sql);

		if($this->handler->error){
			throw new \Exception($this->handler->error);
		}
		$this->rowsAffected = $this->handler->affected_rows;

		return $exec;
	}

	# 执行数据库插入
	public function insert($values,$debug=false)
	{
		if(empty($this->table)) throw new \Exception('缺少数据库表名 $this->db->from()');

		$ks='';
		$vs='';
		foreach($values as $key => $value)
		{
			$ks.=$ks?",`$key`":"`$key`";
			$vs.=$vs?",'{$this->escape($value)}'":"'{$this->escape($value)}'";
		}
		$sql="INSERT INTO `{$this->table}` ($ks) VALUES ($vs)";
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}

	/**
	 * 一次插入多条数据到一张表中
	 *
	 * @param  array  $fields  所有字段 array('category','price','stock');
	 * @param  array  $records 多条字段的数据记录 array(array('friuts','199','999'),array('drinks','199','999'));
	 * @return INSERT INTO TABLE (`category`,`price`,`stock`) VALUES ('friuts', '199', '999'), ('drinks', '199', '999');
	 */
	public function multi_insert($fields=array(),$records=array(),$debug=false)
	{
		if(empty($fields)||empty($records)) return false;
		if(empty($this->table)) throw new \Exception('缺少数据库表名 $this->db->from()');
		$number_fields = count( $fields );
		$sql = "INSERT INTO `{$this->table}` ";
		$keys = array();
		// 遍历所有字段
		foreach ($fields as $field) {
			$keys[] = '`'.$field.'`';
		}
		$keys = "(".implode(',', $keys).")";
		$values = array();
		foreach( $records as $record ){
			// 如果值与字段数相匹配
			if( count($record) == $number_fields )
				$values[] = "('". implode( "', '", array_map(array($this,"escape"),array_values($record))) ."')";
		}
		$values = implode( ", ", $values);
		$sql .= $keys .' VALUES '. $values .';';
		if($debug) throw new \Exception($sql);
		$this->handler->query($sql);
	}

	# 执行数据更新
	public function update($values,$debug=false)
	{
		if(empty($this->table)) throw new \Exception('缺少数据库表名 $this->db->from()');
		$v='';
		if(is_string($values)){
			$v.=$values;
		}else{
			foreach($values as $key => $value){
				$v.=$v?",`$key`='{$this->escape($value)}'":"`$key`='{$this->escape($value)}'";
			}
		}
		$sql="UPDATE `{$this->table}` SET $v";
		if(!empty($this->sql)){
			$sql .= $this->sql;
			$this->sql = '';
		}
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}

	# 执行数据删除
	public function delete($debug=false)
	{
		if(empty($this->table)) throw new \Exception('缺少数据库表名 $this->db->from()');
		$sql="DELETE FROM `{$this->table}`";
		if(!empty($this->sql)){
			$sql .= $this->sql;
			$this->sql = '';
		}
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}

	# 查询单条数据 返回一个数组
	public function row($debug=false)
	{
		$row = [];
		$sql = '';
		if(!empty($this->sql)){
			$sql .= $this->sql;
			$this->sql = '';
		}
		if($debug) throw new \Exception($sql);
		$result=$this->query($sql);
		if ($result){
			$row = $result->fetch_array(MYSQLI_ASSOC);
		}
		return $row;
	}

	# 查询多条数据
	public function rows($debug=false)
	{
		$temp=false;
		$sql = '';
		if(!empty($this->sql)){
			$sql .= $this->sql;
			$this->sql = '';
		}
		if($debug) throw new \Exception($sql);
		$result=$this->query($sql);
		if($result){
			$array = array();
			while ($row = $result->fetch_assoc()){
				$array[] = $row;
			}
			$temp=$array;
			$this->flush($result);
		}
		return $temp;
	}

	# 创建数据库
	public function createDatabase($database)
	{

		$query = "CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET %s COLLATE %s;";
		$result = $this->query(sprintf($query,$database,$this->charset,$this->collate));
		if(!$result){
			throw new \Exception("创建数据库'{$database}'失败！");
		}
	}

	# 删除数据库
	public function dropDatabase($database)
	{

		$result = $this->query(sprintf("DROP DATABASE `%s`;",$database));
		if(!$result){
			throw new \Exception("删除数据库'{$database}'失败！");
		}
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
		return $this->handler->insert_id;
	}
	# 数据库备份
	public function export()
	{
		$sql='';
		$result = $this->query("SHOW TABLES FROM `{$this->database}`");
		if($result){
			$array = array();
			while ($row = $result->fetch_row()){
				$array[] = current($row);
			}
			if(!empty($array)){
				foreach ($array as $v){
					$sql.="DROP TABLE IF EXISTS `$v`;\n";
					$res = $this->query("show create table $v");
					$rs=$res->fetch_row();
					$sql.=$rs[1].";\n\n";
				}
				foreach ($array as $v){
					$res=$this->query("select * from $v");
					$field = $res->field_count;
					while ($rs = $res->fetch_array()) {
						$comma="";
						$sql.="insert into `$v` values(";
						for($i=0;$i<$field;$i++){
							$sql.=$comma."'".$this->handler->escape_string($rs[$i])."'";
							$comma = ",";
						}
						$sql.=");\n";
					}
					$sql.="\n";
				}
			}
		}
		return $sql;
	}

	# 取得当前数据库版本
	public function version()
	{
		return preg_replace('/[^0-9.].*/', '', $this->handler->server_info);
	}

	# 开启事务 数据库引擎为InnoDB时使用此函数
	public function autocommit()
	{
		$this->handler->autocommit(false);
	}

	# 判断执行情况，返回事务状态
	public function checkcommit()
	{
		if($this->handler->errno){
			$this->handler->rollback(); # 执行事务回滚
		}else{
			$this->handler->commit();	# 提交事务
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
	public function prepare($condition, $args)
	{

		if(strpos($condition, '%')){

			if(count($args)>0){

				$condition = str_replace( "'%s'", '%s', $condition );

				$condition = str_replace( '"%s"', '%s', $condition );

				$condition = preg_replace( '|(?<!%)%f|' , '%F', $condition );

				$condition = preg_replace( '|(?<!%)%s|', "'%s'", $condition );

				array_walk( $args, array( $this, 'escape_by_ref' ) );

				return @vsprintf( $condition, $args );
			}
		}

		return $condition;
	}

	private function _real_escape($string)
	{
		if($this->handler){
			return $this->handler->real_escape_string($string);
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
		if (!$this->handler) return false;
		$this->handler->close();
		$this->handler = null;
	}
}