<?php namespace App\Models;

use Illuminate\Database\Capsule\Manager as DB;
use Cafe\Foundation\Model;

use Cafe\Support\Arr;

class Admin extends Model
{
    /* 是否为管理员 */
    public function is($uid)
    {
        return DB::table('usermeta')->where([
            ['key', '=', 'is_admin'],
            ['uid', '=', $uid]
        ])->value('value') === 'true';
    }
    
    // 检查备份目录
    private function checkBackupFolder()
    {
        $dir = app()->storagePath('backup');
        if (! file_exists($dir)) {
            if (! is_writeable(dirname($dir))) {
                die('Backup folder must be writeable to continue.');
            }
            mkdir($dir);
            chmod($dir, 0777);
        }
        return $dir;
    }
    
    // 获取数据库备份文件
    public function getBackupFiles()
    {
        $backupFolder = $this->checkBackupFolder();
        // 读取文件夹
        $array = array();
        if ($handle=opendir($backupFolder)) {
            $no=1;
            while (false!==($file=readdir($handle))) {
                if (strpos($file, '.sql')!==false) {
                    $array[$no]['no']=$no;
                    $array[$no]['file']=$file;
                    $array[$no]['created']=date('Y-m-d H:i:s', filemtime($backupFolder.'/'.$file));
                    $no++;
                }
            }
            closedir($handle);
        }
        return $array;
    }
    
    // 导出指定数据库备份文件
    public function exportBackup()
    {
        $dir = $this->checkBackupFolder();
        $sql = $this->exportDatabase();
        $filename = date('YmdHis') . mt_rand(0, 99999) . '.sql';
        return file_put_contents($dir .'/'. $filename, $sql);
    }
    
    // 还原指定数据库备份文件
    public function restoreBackup($file)
    {
        if (empty($file)) {
            return false;
        }
        $dir = $this->checkBackupFolder();
        $filename = $dir .'/'. $file;
        if (file_exists($filename)) {
            $query = file_get_contents($filename);
            $pdo = DB::connection()->getPdo();
            $pdo->exec($query);
            return true;
        }
        return false;
    }
    
    // 删除指定数据库备份文件
    public function deleteBackup($file)
    {
        if (empty($file)) {
            return false;
        }
        $dir = $this->checkBackupFolder();
        $filename = $dir .'/'. $file;
    
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return false;
    }
    // 导出数据库
    public function exportDatabase()
    {
        $database = DB::selectOne('select database() as name')->name;
        $tables = DB::select('show tables');
        $tables = array_column($tables, "Tables_in_". $database);
        
        $sql = "use {$database};\r\n\r\n";
        foreach($tables as $table) {
            $sql.="DROP TABLE IF EXISTS `$table`;\r\n";
            $rs = DB::selectOne("show create table $table");
            $col = 'Create Table';
            $sql.= $rs->$col.";\r\n\r\n";
            
            $rows = DB::select("select * from $table");
            $count = count($rows);
            
            if($count > 0) {
                foreach($rows as $row) {
                    $values = [];
                    foreach($row as $key => $val) {
                        $values[] = "'".addslashes($val)."'";
                    }
                    $sql.="insert into `$table` values(".implode(',', $values).");\r\n";
                }
            }
        }
        return $sql;
    }
}