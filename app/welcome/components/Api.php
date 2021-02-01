<?php namespace App\Welcome\Components;

use Cafe\Foundation\Component;

class Api extends Component
{
    public function loginLogs($page, $limit)
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return $this->load('welcome@logs')->getData('logged')->page($page, $limit);
    }
    
    public function todolists($page, $limit)
    {
        $uid = $this->session->get('login_uid');
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return $this->load('welcome@todolists')->getData($uid)->page($page, $limit);
    }
    
    public function users($page, $limit)
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return $this->load('welcome@users')->getData($page, $limit);
    }
    
    public function operatelogs($page, $limit)
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, array("min_range"=>1));
        $limit = filter_var($limit, FILTER_VALIDATE_INT, array("max_range"=>100));
        return $this->load('welcome@logs')->getData('operate')->page($page, $limit);
    }
}
