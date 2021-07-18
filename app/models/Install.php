<?php namespace App\Models;

use Cafe\Foundation\Model;
use Illuminate\Database\Capsule\Manager as DataManager;

class Install extends Model
{
    public function importData($config)
    {
        $database = $config['database'];
        
        $db = new DataManager;
        $config['database'] = '';
        $db->addConnection($config);
        $pdo = $db->getConnection()->getPdo();
        
        $version = $pdo->query('SELECT VERSION()')->fetchColumn();
        if (version_compare($version, '5.6.0', '<')) {
            throw new \Exception('MySQL version too low. You need at least MySQL 5.6.');
        }
        $pdo->query('CREATE DATABASE IF NOT EXISTS '.$database.' DEFAULT CHARACTER SET = `'.$config['charset'].'` DEFAULT COLLATE = `'.$config['collation'].'`')->execute();
        
        $config['database'] = $database;
        $db->addConnection($config);
        $db->getConnection()->reconnect();
        $pdo = $db->getConnection()->getPdo();

        $file = app()->configPath('default_install.sql');
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            if (empty($sql)) {
                throw new \Exception("Failed to import table structure and data");
            }
            $pdo->exec($sql);
        } else {
            throw new \Exception("File {$file} not found");
        }
    }
    
    public function lock()
    {
        $fp = fopen(app()->configPath('install.lock'), 'wb');
        fwrite($fp, '');
        fclose($fp);
    }
}