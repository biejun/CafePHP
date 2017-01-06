<?php
if( !defined('ABSPATH') ) exit('Access denied!');
/**
 *	数据库类
 *
 *  @package  Mysql Improvement
 */
class MySqlIm{
	protected $db_prefix = 'any_';
	protected $db_handle = null;
	protected $db_name = '';
	public function __construct($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_created=false,$charset,$collate,$port=null){
		$this->db_prefix = $db_prefix;
		$this->db_name = $db_name;
		$this->db_handle = new mysqli($db_host, $db_user, $db_password, $port);
		if($this->db_handle->connect_error){
			exit("Can't connect MySQL server($db_host)!");
		}
		if($db_created)
			$this->query("CREATE DATABASE IF NOT EXISTS $db_name DEFAULT CHARACTER SET $charset COLLATE $collate;");
		$this->db_handle->select_db($db_name);
		$this->db_handle->set_charset($charset);
		return $this->db_handle;
	}
	# 执行数据库语句
	public function query($query){
		return $this->db_handle->query($query);
	}
	# 多条执行 $query必须为包含有多条语句的字符串
	public function multi_query($query){
		return $this->db_handle->multi_query($query);
	}
	# 执行数据库插入
	public function insert($table,$values,$debug=false){
		$ks='';
		$vs='';
		foreach($values as $key => $value){
				$ks.=$ks?",`$key`":"`$key`";
				$vs.=$vs?",'$value'":"'$value'";
		}
		$sql="INSERT INTO `".$this->db_prefix."$table` ($ks) VALUES ($vs)";
		if($debug)return $sql;
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
	public function multi_insert($table,$fields=array(),$records=array()){
		if(empty($fields)||empty($records)) return false;
		$number_fields = count( $fields );
		$sql = "INSERT INTO `".$this->db_prefix."$table` ";
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
				$values[] = "('". implode( "', '", array_values($record)) ."')";
		}
		$values = implode( ", ", $values);
		$sql .= $keys .' VALUES '. $values .';';
		$this->db_handle->query($sql);
	}
	# 执行数据更新
	public function update($table,$values,$condition='',$debug=false){
		$v='';
		if(is_string($values)){
			$v.=$values;
		}else{
			foreach($values as $key => $value){
				$v.=$v?",$key='$value'":"$key='$value'";
			}
		}
		$sql="UPDATE `".$this->db_prefix."$table` SET $v  WHERE $condition";
		if($debug)return $sql;
		return $this->query($sql);
	}
	# 执行数据删除
	public function delete($table,$condition='',$debug=false){
		if(empty($condition)||$condition==''){
			$sql="DELETE FROM `".$this->db_prefix."$table`";
		}else{
			$sql="DELETE FROM `".$this->db_prefix."$table` WHERE $condition";
		}
		if($debug)return $sql;
		return $this->query($sql);
	}
	# 查询多条数据
	public function rows($table,$field,$where='',$order=''){
		$temp=false;
		$sql = "SELECT $field FROM `".$this->db_prefix."$table`";
		if(!empty($where)) $sql.=" WHERE $where";
		if(!empty($order)) $sql.=" ORDER BY $order";
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
	public function fetch($sql){
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
	public function row($table,$field,$where,$type=MYSQLI_ASSOC){
		$row=false;
		$result=$this->query("SELECT ".$field." FROM `".$this->db_prefix.$table."` WHERE ".$where);
        if ($result){
			$row=(empty($type))?$result->fetch_array():$result->fetch_array($type);
		}
		return $row;
	}
	# 查询单个字段的值
	public function one($table,$field,$where=''){
		if(empty($table)||empty($field))return false;
		$result=$this->row($table,$field,$where,'');
		return $result[0];
	}
	# 判断某个字段的值是否正确
	public function repeat($table,$field,$value){
		$row=$this->row($table,$field,"$field='$value' LIMIT 0,1");
		return $row?true:false;
	}
	# 资源回收
	private function flush($result){
		if( $result instanceof mysqli_result ){
			$result->free();
		}
	}
	# 取得插入数据的ID
	public function id(){
		return $this->db_handle->insert_id;
	}
	# 数据库备份
	public function export(){
		$sql='';
		$result = $this->query('SHOW TABLES FROM `'.$this->db_name.'`');
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
							$sql.=$comma."'".$this->db_handle->escape_string($rs[$i])."'";
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
	public function version(){
		$server_info = $this->db_handle->server_info;
		return preg_replace( '/[^0-9.].*/', '', $server_info );
	}
	# 开启事务 数据库引擎为InnoDB时使用此函数
	public function autocommit(){
		$this->db_handle->autocommit(false);
	}
	# 判断执行情况，返回事务状态
	public function checkcommit(){
		if($this->db_handle->errno){
			$this->db_handle->rollback(); # 执行事务回滚
		}else{
			$this->db_handle->commit();	# 提交事务
		}
	}
	# 关闭连接
	public function __destruct(){
		if (!$this->db_handle) return false;
		$this->db_handle->close();
		$this->db_handle = null;
	}
}