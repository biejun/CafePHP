<?php namespace App\Models;

use Cafe\Foundation\Model;

class AdminLog extends Model
{
    public $data = [];

    public $loggedId = 'admin:logged:history';

    public $operateId = 'admin:operate:history';

    public function add($type = 'logged', $username, $text = '')
    {
        $id = ('logged' === $type) ? $this->loggedId : $this->operateId;
        $data = $this->getData($type)->all();
        $data[] = ['name'=>$username,'time'=>date("Y-m-d H:i"),'text' => $text];
        app()->make('log')->set($id, $data);
    }

    public function getData($type = 'logged')
    {
        $id = ('logged' === $type) ? $this->loggedId : $this->operateId;
        $this->data = app()->make('log')->get($id);
        return $this;
    }

    public function all()
    {
        if (!empty($this->data)) {
            return array_reverse($this->data);
        }
        return array();
    }

    public function page($page = 1, $limit = 10)
    {
        if (!empty($this->data)) {
            return array_reverse(array_slice($this->data, $limit * ($page - 1), $limit));
        }
        return array();
    }

    public function delete($type)
    {
        $id = ('logged' === $type) ? $this->loggedId : $this->operateId;
        return app()->make('log')->delete($id);
    }
}
