<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminApi extends Widget
	{
		public function getUsers($page,$limit)
		{

			$page = ($page) ? intval($page) : 1;

			$limit = ($limit) ? intval($limit) : 20;

			$data = $this->db->rows("users"
				,"`name`,`uid`,`avatar`,FROM_UNIXTIME(`created`, '%Y-%m-%d') AS created,
				FROM_UNIXTIME(`logged`, '%Y-%m-%d') AS logged,`group`"
				,""
				,"created DESC LIMIT ".(($page-1)*$limit).",".$limit);

			if($this->db->rowsAffected > 0){
				return $data;
			}
			return false;
		}
	}
}