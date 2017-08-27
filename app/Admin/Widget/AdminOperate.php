<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminOperate extends Widget
	{
		public $cacheName = 'admin:operate';

		public function setOperate($text)
		{
			$data = $this->getOperates();
			$name = __session('__admin_name__');

			$data[] = ['name'=>$name,'text'=>$text,'time'=>date("Y-m-d H:i:s")];

			$this->cache->set($this->cacheName,$data);
		}

		public function getOperates()
		{
			$data = $this->cache->get($this->cacheName);

			if(!empty($data)){
				return $data;
			}
			return array();
		}
	}
}