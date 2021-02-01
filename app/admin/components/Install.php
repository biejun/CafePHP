<?php namespace App\Admin\Components;

use Cafe\Foundation\Component;

class Install extends Component
{

	public function import()
	{
		$file = CONFIG.'/default_install.sql';
		if(file_exists($file)){
			$sql = file_get_contents($file);
			if(empty($sql)) throw new \Exception("{$file}文件中没有找到可执行的SQL语句");
			foreach (explode(';', $sql) as $query) {
				$query = trim($query);
				if ($query) {
					$this->db($query)->query();
				}
			}
		}else{
			throw new \Exception("没有找到文件{$file}");
		}
	}

	public function lock()
	{
		$fp = fopen(CONFIG . '/install.lock', 'wb');
		fwrite($fp, '');
		fclose($fp);
	}
}