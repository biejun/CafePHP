<?php

namespace Coffee\DataBase;

trait DBTrait
{

	public $charset;

	public $collate;

	public function createDatabase($dbname)
	{

		$query = "CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET %s COLLATE %s;";
		$result = $this->query(sprintf($query,$dbname,$this->charset,$this->collate));
		if(!$result){
			throw new \Exception("创建数据库'{$dbname}'失败！");
		}
	}

	public function deleteDatabase($dbname)
	{

		$result = $this->query(sprintf("DROP DATABASE `%s`;",$dbname));
		if(!$result){
			throw new \Exception("删除数据库'{$dbname}'失败！");
		}
	}

	public function setCharset()
	{
		if ($charset = conf('database','charset')) {
			$this->charset = $charset;
			if ($collate = conf('database','collate')) {
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
}