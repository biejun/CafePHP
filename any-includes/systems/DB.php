<?php
if(!defined('ABSPATH'))exit('Access denied!');
/**
 *	@package MYSQL DATABASE
 */
class DB{
	public $charset = 'utf8';
	public $collate = 'utf8_general_ci';
	protected $db_host;
	protected $db_name;
	protected $db_user;
	protected $db_password;
	protected $db_prefix = 'any_';
	protected $db_handle = null;
	public $is_mysqli = false;
	public function __construct($db_host,$db_name,$db_user,$db_password,$db_prefix,$db_create=false){
		if(function_exists( 'mysqli_connect' )) $this->is_mysqli = true;
		$this->db_host = $db_host;
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_password = $db_password;
		$this->db_prefix = $db_prefix;
		$this->db_connect($db_create);
	}
	public function db_connect($db_create){
		if($this->is_mysqli){
			$this->db_handle = mysqli_init();
			$port = null;
			$socket = null;
			$host = $this->db_host;
			$port_or_socket = strstr( $host, ':' );
			if ( ! empty( $port_or_socket ) ) {
				$host = substr( $host, 0, strpos( $host, ':' ) );
				$port_or_socket = substr( $port_or_socket, 1 );
				if ( 0 !== strpos( $port_or_socket, '/' ) ) {
					$port = intval( $port_or_socket );
					$maybe_socket = strstr( $port_or_socket, ':' );
					if ( ! empty( $maybe_socket ) ) {
						$socket = substr( $maybe_socket, 1 );
					}
				} else {
					$socket = $port_or_socket;
				}
			}
			mysqli_real_connect( $this->db_handle, $host, $this->db_user, $this->db_password, null, $port, $socket,0);
			if($this->db_handle->connect_errno){
				exit("Can't connect MySQL server($this->db_host)!");
			}
		}else{
			$this->db_handle = mysql_connect($this->db_host,$this->db_user,$this->db_password,true) or exit("Can't connect MySQL server($this->db_host)!");
		}
		if($this->db_handle){
			$query = "SET NAMES $this->charset COLLATE $this->collate";
			if($db_create){
				$this->query("CREATE DATABASE IF NOT EXISTS $this->db_name DEFAULT CHARACTER SET $this->charset COLLATE $this->collate;");
			}
			if ( $this->is_mysqli ) {
				mysqli_select_db($this->db_handle,$this->db_name) or exit("Can't select MySQL database($this->db_name)!");;
				if (function_exists( 'mysqli_set_charset')){
					mysqli_set_charset($this->db_handle, $this->charset);
				}else{
					mysqli_query($this->db_handle,$query);
				}
			} else {
				mysql_select_db($this->db_name,$this->db_handle) or exit("Can't select MySQL database($this->db_name)!");;
				if ( function_exists( 'mysql_set_charset' )) {
					mysql_set_charset($this->charset, $this->db_handle);
				} else {
					mysql_query($query,$this->db_handle);
				}
			}
		}
	}
	public function query($query){
		if ( $this->is_mysqli ) {
			return mysqli_query($this->db_handle,$query);
		}else{
			return mysql_query($query,$this->db_handle);
		}
	}
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
	public function delete($table,$condition='',$debug=false){
		if(empty($condition)||$condition==''){
			$sql="DELETE FROM `".$this->db_prefix."$table`";
		}else{
			$sql="DELETE FROM `".$this->db_prefix."$table` WHERE $condition";
		}
		if($debug)return $sql;
		return $this->query($sql);
	}
	public function rows($table,$field,$where=''){
		$temp=false;
		$sql = "SELECT $field FROM `".$this->db_prefix."$table`";
		if(!empty($where)) $sql.=" WHERE $where";
        $result=$this->query($sql);
        if($result){
            $array = array();
            if($this->is_mysqli){
	            while ($row = mysqli_fetch_assoc($result)){
	                $array[] = $row;
	            }
            }else{
	            while ($row = mysql_fetch_assoc($result)){
	                $array[] = $row;
	            }
            }
            $temp=$array;
			$this->flush($result);
        }
		return $temp;
    }
	# 查询单行数据
	public function row($table,$field,$where){
		$temp=false;
		$result=$this->query("SELECT $field FROM `".$this->db_prefix."$table` WHERE $where");
        if ($result){
			$temp=($this->is_mysqli)?mysqli_fetch_array($result):mysql_fetch_array($result);
			$this->flush($result);
		}
		return $temp;
	}
	public function val($table,$field,$where=''){
		if(empty($table)||empty($field))return false;
		$result=$this->row($table,$field,$where);
		return $result[0];
	}
	public function repeat($table,$field,$value){
		$row=$this->row($table,$field,"`$field`=$value LIMIT 0,1");
		return $row?true:false;
	}
	private function flush($result){
		if( $this->is_mysqli && $result instanceof mysqli_result ){
			mysqli_free_result($result);
		}elseif( is_resource( $result ) ){
			mysql_free_result($result);
		}
	}
	public function id(){
		if($this->is_mysqli){
			return mysqli_insert_id($this->db_handle);
		}else{
			return mysql_insert_id($this->db_handle);
		}
	}
	public function version(){
		if( $this->is_mysqli ){
			$server_info = mysqli_get_server_info($this->db_handle);
		}else{
			$server_info = mysql_get_server_info($this->db_handle);
		}
		return preg_replace( '/[^0-9.].*/', '', $server_info );
	}
	public function __destruct(){
		if (!$this->db_handle){
			return false;
		}
		if ($this->is_mysqli){
			mysqli_close($this->db_handle);
		}else{
			mysql_close($this->db_handle);
		}
		$this->db_handle = null;
	}
}