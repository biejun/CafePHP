<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Install extends Component
{

	public function import($sqlFile)
	{
		$sql = file_get_contents($sqlFile);
		if(empty($sql)) throw new \Exception("{$sqlFile}文件中没有找到可执行的SQL语句", 1);
		$sql = str_replace('$prefix$',$this->db->prefix, $sql);
		$sql = str_replace('$charset$',$this->db->charset, $sql);
		$sql = str_replace('$collate$',$this->db->collate, $sql);
		foreach (explode(';', $sql) as $query) {
			$query = trim($query);
			if ($query) {
				$this->db->query($query);
			}
		}
	}

	public function lock()
	{
		$fp = fopen(CONFIG . 'install.lock', 'wb');
		fwrite($fp, '');
		fclose($fp);
	}
}