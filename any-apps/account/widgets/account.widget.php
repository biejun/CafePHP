<?php
if(!defined('ABSPATH'))exit('Access denied!');

class account_widget extends Widget{
	public $table = 'user_profile';
	public function get_avatar($uid,$def = 'any-includes/statics/img/avatar.jpg'){
		$user_avatar =  $this->db->one($this->table,"COUNT(`follow_id`)","user_id='$uid'");
		return ($user_avatar)?$user_avatar:PATH.$def;
	}
}