<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminApi extends Widget
	{
		public function getSiteConfig()
		{
			$data = $this->cache->get('site:configs');
			if(!$data){
				$data = $this->db->rows('configs','*','`group` = 1');
				$this->cache->add('site:configs',$data);
			}
			return $data;
		}
	}
}