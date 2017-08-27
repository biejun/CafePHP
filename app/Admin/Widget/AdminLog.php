<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminLog extends Widget
	{
		public $cacheName = 'admin:log';

		public function setLog($username, $time)
		{
            $data = $this->getlogs(NULL);

            $data[] = ['name'=>$username,'time'=>date("Y-m-d H:i",$time),'city'=> getCity()];

            $this->cache->set($this->cacheName,$data);
		}

		public function getLogs($page = 1, $limit = 20)
		{
			$data = $this->cache->get($this->cacheName);
			if( !empty($data) ){
				if(is_null($page)){
					return array_reverse(array_slice($data, $limit * ($page - 1), $limit));
				}
				return array_reverse($data);
			}
			return array();
        }
        
        public function deleteLogs()
        {

        }
	}
}