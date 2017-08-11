<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminOperate extends Widget
	{
		public $operate = 'admin:operate';

		public function setOperate($text)
		{
			$data = $this->getOperate();
			$name = __session('__admin_name__');

			$data[] = ['name'=>$name,'text'=>$text,'time'=>date("Y-m-d H:i:s")];

			$this->cache->set($this->operate,$data);
		}

		public function getOperate()
		{
			$data = $this->cache->get($this->operate);

			if(!empty($data)){
				return $data;
			}
			return array();
		}
	}
}