<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Api extends Model
{
    public function logged_logs($page, $limit)
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return model('AdminLog')->getData('logged')->page($page, $limit);
    }
	
	public function operate_logs($page, $limit)
	{
	    $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
	    $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
	    return model('AdminLog')->getData('operate')->page($page, $limit);
	}
    
    // public function todolists($page, $limit)
    // {
    //     $uid = $this->session->get('login_uid');
    //     $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
    //     $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
    //     return $this->load('welcome@todolists')->getData($uid)->page($page, $limit);
    // }
    
    public function users($page, $limit)
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return model('Users')->getDataWithDetail($page, $limit);
    }
}
