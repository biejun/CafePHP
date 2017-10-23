<?php
namespace App\Admin\Components;

use Coffee\Foundation\Component;

class Admin extends Component
{

	public function createTables($sqls)
	{
		foreach ($sqls as $query) {
			$query = trim($query);
			if ($query) {
				//$this->db->query($query);
			}
		}
	}

	public function add($usernmme,$password)
	{

	}
}