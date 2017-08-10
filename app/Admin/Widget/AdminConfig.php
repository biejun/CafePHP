<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminConfig extends Widget{

		public $table = 'configs';

		public function updateSiteConfigs($data){

			foreach ($data as $key => $value) {
				$this->db->update($this->table,$value," `name` = '$key' and `group` = '1'");
			}
			$this->cache->delete('site:configs');
		}

	}
}