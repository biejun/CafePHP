<?php namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Admin extends Component
{

	public function querys($sqls)
	{
		$sql = str_replace('%prefix%',G('database','prefix'), $sql);
		$sql = str_replace('%charset%',G('database','charset'), $sql);
		$sql = explode(';', $sql);
		foreach ($sqls as $query) {
			$query = trim($query);
			if ($query) {
				$this->db->query($query);
			}
		}
	}
}