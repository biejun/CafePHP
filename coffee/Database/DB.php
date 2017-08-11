<?php

namespace Coffee\DataBase;

class DB
{
	use DBTrait;

	public $prefix = 'any_';

	protected $dbname;

	public $rowsAffected;

	protected $handler = null;

	public function __construct($db)
	{
		if(count($db) > 0){

			$this->prefix = $db['prefix'];
			$this->dbname = $db['name'];
			$this->handler = new \mysqli($db['host'], $db['user'], $db['password'], $db['port']);
			if($this->handler->connect_errno){
				throw new \Exception('Connect Error (' . $this->handler->connect_errno . ') '. $this->handler->connect_error);
			}
			$this->setCharset();
		}else{
			throw new \Exception('数据库初始化失败，请检查数据库配置文件');
		}
	}

	public function connect($created = false)
	{
		$exists = $this->handler->select_db($this->dbname);
		if(!$exists){
			// 自动创建数据库
			if($created){
				$this->createDatabase($this->dbname);
				$this->handler->select_db($this->dbname);
			}else{
				throw new \Exception("Can't select MySQL database(".$this->dbname.")!");
			}
		}
		$this->handler->set_charset($this->charset);

		return $this;
	}

	# 执行一条数据库语句
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
	public function insert($table,$values,$debug=false)
	{
		$ks='';
		$vs='';
		foreach($values as $key => $value)
		{
			$ks.=$ks?",`$key`":"`$key`";
			$vs.=$vs?",'{$this->escape($value)}'":"'{$this->escape($value)}'";
		}
		$sql="INSERT INTO `{$this->prefix}$table` ($ks) VALUES ($vs)";
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}
	/**
	 * 一次插入多条数据到一张表中
	 *
	 * @param  string $table   数据库表名
	 * @param  array  $fields  所有字段 array('category','price','stock');
	 * @param  array  $records 多条字段的数据记录 array(array('friuts','199','999'),array('drinks','199','999'));
	 * @return INSERT INTO TABLE (`category`,`price`,`stock`) VALUES ('friuts', '199', '999'), ('drinks', '199', '999');
	 */
	public function multi_insert($table,$fields=array(),$records=array(),$debug=false)
	{
		if(empty($fields)||empty($records)) return false;
		$number_fields = count( $fields );
		$sql = "INSERT INTO `{$this->prefix}$table` ";
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
	public function update($table,$values,$condition='',$debug=false)
	{
		$v='';
		if(is_string($values)){
			$v.=$values;
		}else{
			foreach($values as $key => $value){
				$v.=$v?",`$key`='{$this->escape($value)}'":"`$key`='{$this->escape($value)}'";
			}
		}
		$sql="UPDATE `{$this->prefix}$table` SET $v WHERE $condition";
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}

	# 执行数据删除
	public function delete($table,$condition='',$debug=false)
	{
		if(empty($condition)||$condition==''){
			$sql="DELETE FROM `{$this->prefix}$table`";
		}else{
			$sql="DELETE FROM `{$this->prefix}$table` WHERE $condition";
		}
		if($debug) throw new \Exception($sql);
		return $this->query($sql);
	}

	# 查询多条数据
	public function rows($table,$field,$condition='',$order='',$debug=false)
	{
		$temp=false;
		$sql = "SELECT $field FROM `{$this->prefix}$table`";
		if(!empty($condition)) $sql.=" WHERE $condition";
		if(!empty($order)) $sql.=" ORDER BY $order";
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

	# 解析一条数据库语句，返回多条数据
	public function fetch($sql)
	{
		$temp=false;
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

	# 查询单条数据 返回一个数组
	public function row($table,$field,$condition,$type=MYSQLI_ASSOC,$debug=false)
	{
		$row = [];
		$sql = "SELECT $field FROM `{$this->prefix}$table` WHERE $condition";
		if($debug) throw new \Exception($sql);
		$result=$this->query($sql);
		if ($result){
			$row = $result->fetch_array($type);
		}
		return $row;
	}

	# 查询单个字段的值
	public function one($table,$field,$condition,$debug=false)
	{
		if(empty($table)||empty($field)||empty($condition)) return false;
		$row=$this->row($table,$field,$condition,MYSQLI_NUM,$debug);
		return $row[0];
	}

	# 判断某个字段的值是否正确
	public function repeat($table,$field,$condition,$debug=false)
	{
		if(empty($table)||empty($field)||empty($condition)) return false;
		$row=$this->row($table,$field,$condition,MYSQLI_BOTH,$debug);
		return (bool) $row;
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
		$result = $this->query("SHOW TABLES FROM `{$this->dbname}`");
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

	private function _real_escape($string)
	{
		if($this->handler){
			return $this->handler->real_escape_string($string);
		}
		return addslashes($string);
	}

	# 关闭连接
	public function __destruct()
	{
		if (!$this->handler) return false;
		$this->handler->close();
		$this->handler = null;
	}
}