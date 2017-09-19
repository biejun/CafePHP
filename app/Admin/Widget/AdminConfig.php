<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminConfig extends Widget{

		public $table = 'configs';

		private $group = 1;

		public function setGroup($group)
		{
			$this->group = (int) $group;

			return $this;
		}

		public function get()
		{
			$data = $this->cache->get('site:configs:'.$this->group);
			if(!$data){
				$data = $this->db->rows('configs','*','`group` ='.$this->group);
				$this->cache->add('site:configs:'.$this->group,$data);
			}
			return $data;
		}

		public function set($data){

			foreach ($data as $key => $value) {
				$this->db->update($this->table,$value," `name` = '$key' and `group` = ".$this->group);
			}
			$this->cache->delete('site:configs:'.$this->group);

			return true;
		}

	}
}