<?php
if( !defined('ABSPATH') ) exit('Access denied!');
/**
 *	数据库类
 *
 *  @package  MySql
 */
class MySql{
	protected $db_prefix = 'any_';
	protected $db_handle = null;
	public function __construct($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_created=false,$charset,$collate){
		$this->db_prefix = $db_prefix;
		$this->db_handle = mysql_connect($db_host,$db_user,$db_password,true) or exit("Can't connect MySQL server($db_host)!");

		if($this->db_handle){
			if($db_created)# 创建一个库
				$this->query("CREATE DATABASE IF NOT EXISTS $db_name DEFAULT CHARACTER SET $charset COLLATE $collate;");
			mysql_select_db($db_name,$this->db_handle) or exit("Can't select MySQL database($db_name)!");
			if ( function_exists( 'mysql_set_charset' )) {
				mysql_set_charset($charset, $this->db_handle);
			} else {
				$this->query("SET NAMES $charset COLLATE $collate");
			}
		}
		return $this->db_handle;
	}
	# 执行数据库语句
	public function query($query){
		return mysql_query($query,$this->db_handle);
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
		$this->query($sql);
	}
	# 执行数据库更新
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
			while ($row = mysql_fetch_assoc($result)){
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
			while ($row = mysql_fetch_assoc($result)){
				$array[] = $row;
			}
			$temp=$array;
			$this->flush($result);
		}
		return $temp;
    }
	# 查询单条数据 返回一个数组
	public function row($table,$field,$where){
		$row=false;
		$result=$this->query("SELECT `".$field."` FROM `".$this->db_prefix.$table."` WHERE ".$where);
        if ($result){
			$row=mysql_fetch_array($result);
			$this->flush($result);
		}
		return $row;
	}
	# 查询单个字段的值
	public function one($table,$field,$where=''){
		if(empty($table)||empty($field))return false;
		$result=$this->row($table,$field,$where);
		return $result[0];
	}
	# 判断某个字段的值是否正确
	public function repeat($table,$field,$value){
		$row=$this->row($table,$field,"$field='$value' LIMIT 0,1");
		return $row?true:false;
	}
	# 资源回收
	private function flush($result){
		if( is_resource( $result ) ){
			mysql_free_result($result);
		}
	}
	function table(){
		$array=array();
		$result=mysql_list_tables($this->db_name);
		while ($row = mysql_fetch_row($result))$array[]=$row[0];
		return $array;
	}
	function export(){
		$table=$this->table();
		$sql='';
		foreach ($table as $v){
			$sql.="DROP TABLE IF EXISTS `$v`;\n";
			$rs=mysql_fetch_row(mysql_query("show create table $v"));
			$sql.=$rs[1].";\n\n";
		}
		foreach ($table as $v){
			$res=$this->query("select * from $v");
			$fild=mysql_num_fields($res);
			while ($rs=mysql_fetch_array($res)){
				$comma="";
				$sql.="insert into $v values(";
				for($i=0;$i<$fild;$i++){
					$sql.=$comma."'".mysql_escape_string($rs[$i])."'";
					$comma = ",";
				}
				$sql.=");\n";
			}
			$sql.="\n";
		}
		return $sql;
	}
	# 取得插入数据的ID
	public function id(){
		return mysql_insert_id($this->db_handle);
	}
	# 取得当前数据库版本
	public function version(){
		$server_info = mysql_get_server_info($this->db_handle);
		return preg_replace( '/[^0-9.].*/', '', $server_info );
	}
	public function __destruct(){
		if (!$this->db_handle) return false;
		mysql_close($this->db_handle);
		$this->db_handle = null;
	}
}