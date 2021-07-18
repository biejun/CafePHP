<?php namespace App\Models;

use Cafe\Foundation\Model;
use Cafe\Support\Str;

class TodoLists extends Model
{
    public $data = array();

    /* 待办记录ID [组件所在应用]:[用户ID]:[组件名] */
    public function id($uid)
    {
        return "admin:{$uid}:todolist";
    }

    /* 获取数据 */
    public function getData($uid)
    {
        $this->data = app()->make('data')->get($this->id($uid));
        return $this;
    }

    /* 添加待办 */
    public function add($uid, $text, $level = 1)
    {
        $data = $this->getData($uid)->all();

        $todo = [
            'text'=>$text,
            'time' => date('Y-m-d H:i:s'),
            'complete' => 0,
            'level' => $level
        ];

        $data[] = $todo;

        app()->make('data')->set($this->id($uid), $data);

        return $todo;
    }

    /* 获取全部数据 */
    public function all()
    {
        if (!empty($this->data)) {
            return array_reverse($this->data);
        }
        return array();
    }

    /* 数据分页 */
    public function page($page = 1, $limit = 10)
    {
        if (!empty($this->data)) {
            return array_reverse(array_slice($this->data, $limit * ($page - 1), $limit));
        }
        return array();
    }

    /* 已完成 */
    public function complete($uid, $time)
    {
        $data = $this->getData($uid)->all();
        foreach ($data as &$value) {
            if ($value['time'] === $time) {
                $value['complete'] = 1;
                break;
            }
        }
        app()->make('data')->set($this->id($uid), $data);
    }

    /* 删除待办 */
    public function delete($uid, $todos = '')
    {
        $data = array_reverse($this->getData($uid)->all());
        $data = array_filter($data, function ($v) use ($todos) {
            return !Str::contains($todos, $v['time']);
        });
        app()->make('data')->set($this->id($uid), $data);
    }
}
