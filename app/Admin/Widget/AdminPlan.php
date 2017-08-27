<?php

namespace App\Admin\Widget
{
	use Coffee\Foundation\Widget;

	class AdminPlan extends Widget
	{
		public $table = 'plans';

		public function add($text, $level, $uid)
		{
            if(empty($text)) return false;

            $this->db->insert($this->table,[
                'text'=>$text,
                'level'=>$level,
                'uid'=>$uid
            ]);

			if($this->db->rowsAffected > 0){
				return true;
			}
			return false;
        }
        
        public function getPlanByUid($uid)
        {
            return $this->db->rows($this->table,"*","`uid` = '{$uid}' ORDER BY level DESC");
        }

        public function updatePlanStatus($pid)
        {
            $this->db->update($this->table,"`status` = '1'","`pid` = '{$pid}'");
        }
	}
}